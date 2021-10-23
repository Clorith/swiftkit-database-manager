<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Marius
 * Date: 01.09.12
 * Time: 10:06
 * To change this template use File | Settings | File Templates.
 */
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

    $db->query("
        UPDATE
            `calc_" . strtolower($_POST['skill']) . "`
        SET
            `{$_POST['column']}` = " . $db->quote( $_POST['value'] ) . "
        WHERE
            `{$_POST['parent']}` = " . $db->quote( $_POST['parent_val'] ) . "
        LIMIT 1
    ");

    logging( $_GET['skill'] . ' Calc', '1', $_POST['parent_val'] );