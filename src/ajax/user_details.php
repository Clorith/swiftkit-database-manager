<?php
    // No user session, not a logged in user, kill it.
    if (! isset( $_COOKIE['user_id'] ) )
        die();

    //  This is used in an ajax call, there's not an easy way of securing it, but this is a start, check if referring site is on the same host as us
    if (! stristr( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) )
        die();

    define('SKDBM', true);
    require_once( '../includes/config.php' );
    require_once( '../includes/functions.php' );

    //  Check user access, if less than 1, this is a disabled account or failed IP validation
    if ( ua() < 100 )
        die();

    $data = $db->query( "SELECT `uid`,`username`,`access` FROM `users` WHERE `uid` = '{$_POST['user']}' LIMIT 1" );
    $data = $data->fetch();

    echo json_encode( $data );