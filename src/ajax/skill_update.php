<?php
/**
 * User: Marius Jensen
 * Date: 05.09.12
 * Time: 08:25
 */

    @session_start();

    // No user session, not a logged in user, kill it.
    if (! isset( $_COOKIE['user_id'] ) )
        die( 'You are not logged in' );

    //  This is used in an ajax call, there's not an easy way of securing it, but this is a start, check if referring site is on the same host as us
    if (! stristr( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) )
        die( 'Cross-domain access attempted, we do not approve.' );

    define('SKDBM', true);
    require_once( '../includes/config.php' );
    require_once( '../includes/functions.php' );

    //  Check user access, if less than 1, this is a disabled account or failed IP validation
    if ( ua() < 1 )
        die( 'This account has been disabled.' );


    //  The skill name might've been formatted to look better on the frontend, so we force lowercase here
    $_GET['skill'] = strtolower( $_GET['skill'] );

    $qry = "UPDATE `calc_{$_GET['skill']}` SET ";
    foreach ($_POST AS $key => $val)
    {
        if (in_array($key, array('edit_key', 'edit_value')))
            continue;

        $qry .= "`{$key}` = " . $db->quote( $val ) . ",";
    }
    $qry = substr($qry,0,-1);

    $qry .= " WHERE `{$_POST['edit_key']}` = " . $db->quote( $_POST['edit_value'] ) . " LIMIT 1";

    $update = $db->query( $qry );

    if ( $update->rowCount() >= 1 )
        logging( $_GET['skill'] . ' Calc', '1', $_POST['item'], $_POST['edit_value'] );