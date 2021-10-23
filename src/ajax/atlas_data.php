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

    $_POST['atlas'] = strtolower( $_POST['atlas'] );
    $types = array();

    foreach ($db->query( "DESCRIBE `atlas_{$_POST['atlas']}`" ) AS $fielder)
    {
        if ($fielder['Key'] == 'PRI')
            continue;

        $type = explode("(", $fielder['Type']);
        $types[$fielder['Field']] = $type[0];
    }

    $json = array();

    foreach ($db->query( "
            SELECT
                *
            FROM
                `atlas_" . $_POST['atlas'] . "`
            WHERE
                `" . $_POST['edit_key'] . "` = " . $db->quote( $_POST['edit_value'] ) ) AS $row)
    {
        foreach ($types AS $field => $type)
        {
            $json{$field} = $row[$field];
        }
    }

    echo json_encode( $json );