<?php
/**
 * User: Marius Jensen
 * Date: 30.08.12
 * Time: 14:50
 */
@session_start();
if ( isset( $_COOKIE['user_id'] ) ) {
    setcookie( 'user_id', '', time() - 3600 );
    setcookie( 'user_name', '', time() - 3600 );
}

define('SKDBM', true);

//  This array will be populated if there are errors or failed logins so we can display them in a proper manner
$notice = array(
    'type'    => '',
    'message' => ''
);

if ( isset( $_GET['nosmart'] ) )
{
    require_once( 'includes/config.php' );

    $user_check = $db->prepare( 'SELECT `ip_lock` FROM `users` WHERE `uid` = ? LIMIT 1' );
    $iplock = $db->prepare( 'UPDATE `users` SET `ip_lock` = ? WHERE `uid` = ? LIMIT 1' );

    $user_check->execute( array( $_GET['nosmart'] ) );
    $user = $user_check->fetch();
    if (! $user['ip_lock'] )
    {
        $iplock->execute( array( $_SERVER['REMOTE_ADDR'], $_GET['nosmart'] ) );
    }
}

if ( isset( $_POST['login'] ) )
{
    //  Since we are logged in, we need the two factor files
    require_once( 'includes/lib/twofactor.php' );

    //  We also need our config file, since we need to make a SQL query, and we want to do that using the PDO engine
    require_once( 'includes/config.php' );

    /**
     * Prepare some statements so that we can work against people trying to do funky shit to us
     * The first statement is to grab the auth key, access level and user id from the users database if the user is registered
     * The second statement is used to verify the auth-code is new and has not been used before (to prevent hijacked codes if someone should fuck up bigtime)
     * Third statement is for inserting a new unused token to the tracking table
     */
    $user_check = $db->prepare( 'SELECT `auth`,`access`,`uid`,`remember_auth`,`ip_lock` FROM `users` WHERE `username` = ? AND `password` = ? LIMIT 1' );
    $auth_check = $db->prepare( 'SELECT `tid` FROM `tokens` WHERE `token` = ? AND `user` = ? LIMIT 1' );
    $auth_log   = $db->prepare( 'INSERT INTO `tokens` (`token`,`user`,`time`) VALUES( ?, ?, ? )' );
    $auth_save  = $db->prepare( 'UPDATE `users` SET `remember_auth` = ? WHERE `uid` = ? LIMIT 1' );
    $lip        = $db->prepare( 'UPDATE `users` SET `lastip` = ?, `login_time` = ? WHERE `uid` = ? LIMIT 1' );

    //  sha1 the password, it's not the highest security but beats plaintext and combined with the G-Auth this should be more than sufficient.
    $_POST['password'] = sha1( $_POST['password'] );

    $user_check->execute( array( $_POST['login'], $_POST['password'] ) );
    $user = $user_check->fetch();

    if (! $user )
    {
        $notice['type'] = 'error';
        $notice['message'] = 'The username or password you provided was wrong.';
    }
    elseif ( empty( $user['auth'] ) && ! $user['ip_lock'] )
    {
        //  We'll use this later, if there's no auth token found we will generate one and display a QR code for the user (on first time login or if they manage to lose it somehow, admins can clear the token if need be)
        $make_auth = true;
    }
    else {
        if (! empty( $user['remember_auth'] ) && $user['remember_auth'] == $_SERVER['REMOTE_ADDR'] )
        {
            $lip->execute( array( $_SERVER['REMOTE_ADDR'], time(), $user['uid'] ) );
            setcookie( 'user_id', $user['uid'] );
            setcookie( 'user_name', $_POST['login'] );
            header( 'Location: index.php' );
        }
        elseif (! empty( $user['ip_lock'] ) && $user['ip_lock'] == $_SERVER['REMOTE_ADDR'] )
        {
            $lip->execute( array( $_SERVER['REMOTE_ADDR'], time(), $user['uid'] ) );
            setcookie( 'user_id', $user['uid'] );
            setcookie( 'user_name', $_POST['login'] );
            header( 'Location: index.php' );
        }
        else {
            $auth_check->execute( array( $_POST['token'], $user['uid'] ) );
            $auth = $auth_check->fetch();
            if ( $auth )
            {
                $notice['type'] = 'error';
                $notice['message'] = "The code you submitted has already been used.";
            }
            else {
                //  Code is good, and so are user credentials, let's check that the code is correct and log them in, scotty.
                $InitalizationKey = $user['auth'];					// Set the inital key, fetched by looking up the user.

                $TimeStamp	  = Google2FA::get_timestamp();
                $secretkey 	  = Google2FA::base32_decode( $InitalizationKey );	// Decode it into binary
                $otp       	  = Google2FA::oath_hotp( $secretkey, $TimeStamp );	// Get current token

                // Use this to verify a key as it allows for some time drift.

                $result = Google2FA::verify_key( $InitalizationKey, $_POST['token'] );

                if (! $result )
                    echo 'Token incorrect, cancel login';

                else {
                    $auth_log->execute( array( $_POST['token'], $user['uid'], time() ) );

                    if ( isset( $_POST['save'] ) )
                        $auth_save->execute( array( $_SERVER['REMOTE_ADDR'], $user['uid'] ) );

                    $lip->execute( array( $_SERVER['REMOTE_ADDR'], time(), $user['uid'] ) );
                    setcookie( 'user_id', $user['uid'] );
                    setcookie( 'user_name', $_POST['login'] );
                    header( 'Location: index.php' );
                }
            }
        }
    }
}

if ( isset( $make_auth ) )
{
    //  Login was done, but no auth token provided, this means the account is new or has had the token reset, generate a new one and provide a QR code
    $new_code = Google2FA::generate_secret_key();

    $db->query("
        UPDATE
            `users`
        SET
            `auth` = '" . $new_code . "'
        WHERE
            `username` = '{$_POST['login']}'
        LIMIT 1
    ");

    $title = "SwiftKit";

    $encode = urlencode( "otpauth://totp/" . $title . "?secret=" . $new_code );
    $qr = "https://chart.googleapis.com/chart?cht=qr&amp;chs=400x400&amp;chld=H|0&amp;chl=" . $encode;
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SwiftKit DataBase manager</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style type="text/css">
                /* Override some defaults */
            html, body {
                background-color: #eee;
            }
            body {
                padding-top: 40px;
            }
            .container {
                width: 390px;
            }

                /* The white background content wrapper */
            .container > .content {
                background-color: #fff;
                padding: 20px;
                margin: 0 -20px;
                -webkit-border-radius: 10px 10px 10px 10px;
                -moz-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
            }
            .container > .content .mini-icons {
                margin-right: 45px;
            }

            .login-form {
                margin-left: 65px;
            }

            legend {
                margin-right: -50px;
                font-weight: bold;
                color: #404040;
            }

        </style>

        <link rel="stylesheet" href="css/colorbox.css" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>

    </head>
    <body>
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="login-form">
                    <h2>Login</h2>
                    <form action="login.php" method="post">
                        <div class="control-group">
                            <label class="control-label" for="token">Your Auth token</label>
                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" name="login" id="token" value="<?php echo $new_code; ?>" readonly="readonly">
                                    <a href="<?php echo $qr; ?>" class="ajax cboxElement btn" type="button" title="SwiftKit DataBase Manager Authentication Token">
                                        View QR
                                    </a>
                                </div>
                                <div class="help-block">
                                    <small class="pull-left">
                                        <a href="login.php?nosmart=<?php echo $user['uid']; ?>">I don't have a smart device</a>
                                    </small>
                                    <small class="pull-right mini-icons">
                                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">
                                            <img src="images/play.png" width="25" alt="Google Play" />
                                        </a>

                                        <a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">
                                            <img src="images/itunes.png" width="25" alt="iTunes for iOS" />
                                        </a>

                                        <a href="https://www.blackberry.com" target="_blank">
                                            <img src="images/blackberry.jpg" width="25" alt="Blackberry" />
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <a href="login.php" class="btn btn-primary">Return to login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
    <script type="text/javascript">
        $(document).ready(function() {
            $(".ajax").colorbox({
                iframe: true,
                innerWidth: 400,
                innerHeight: 400
            });
        });
    </script>
    </body>
    </html>


<?php
}
else {
    //  We can display the login form
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SwiftKit DataBase manager</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style type="text/css">
                /* Override some defaults */
            html, body {
                background-color: #eee;
            }
            body {
                padding-top: 40px;
            }
            .container {
                width: 300px;
            }

                /* The white background content wrapper */
            .container > .content {
                background-color: #fff;
                padding: 20px;
                margin: 0 -20px;
                -webkit-border-radius: 10px 10px 10px 10px;
                -moz-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
            }

            .login-form {
                margin-left: 65px;
            }

            legend {
                margin-right: -50px;
                font-weight: bold;
                color: #404040;
            }

        </style>

    </head>
    <body>
    <div class="container">
        <div class="content">
            <?php
                if ( ! empty( $notice['type'] ) )
                    echo '<div class="alert alert-' . $notice['type'] . '">' . $notice['message'] . '</div>';
            ?>
            <div class="row">
                <div class="login-form">
                    <h2>Login</h2>
                    <form action="login.php" method="post">
                        <div class="control-group">
                            <label class="control-label" for="login">Username</label>
                            <div class="controls">
                                <input type="text" name="login" id="login" placeholder="Username">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password">Password</label>
                            <div class="controls">
                                <input type="password" name="password" id="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="token">Authentication Token</label>
                            <div class="controls">
                                <input type="text" name="token" id="token" placeholder="Auth token">
                                <span class="help-block">
                                    <small>If you do not have an authentication token (first time you login or if you use IP-Lock), please leave this field blank.</small>
                                </span>
                                <label class="checkbox">
                                    <input name="save" type="checkbox" value="">
                                    Remember my token
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <button class="btn btn-primary" type="submit">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- /container -->
    </body>
    </html>
<?php
}
?>
