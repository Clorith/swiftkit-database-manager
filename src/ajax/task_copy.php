<?php
    @session_start();

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
    if ( ua() < 1 )
        die();

    $data = $db->query( "
        SELECT
            *
        FROM
            `achievement_diaries`
        WHERE
            `id` = " . addslashes( $_POST['id'] ) . "
        LIMIT 1
    " );

    $data = $data->fetch( PDO::FETCH_ASSOC );

    setcookie( 'SKDBM-Data-Task', serialize( $data ), 0, '/' );
    echo $data['task'];