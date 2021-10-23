<?php
/**
 * Used for redirects if a users authentication fails they will be presented with the following page, including a nice fault message of course.
 * User: Marius
 * Date: 04.09.12
 * Time: 16:35
 */
?>

<html>
    <head>
        <link rel="stylesheet" href="css/colorbox.css" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
    </head>

    <body bgcolor="D4D5D7">
        <div style="text-align: center; margin: 0 auto;">
            <form action="login.php" method="post">
                <table style="text-align: center; margin: 0 auto; margin-top: 30px;">
                    <tr>
                        <td>
                            <img src="images/logo.gif" alt="SwiftKit" />
                        </td>
                    </tr>
                    <tr>
                        <td><strong>You are not authorized to view this page</strong></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo $redirect_msg; ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <a href="login.php">Return to login</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>