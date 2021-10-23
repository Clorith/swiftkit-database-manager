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

    $log_page = ( !isset( $_GET['page'] ) ? 1 : $_GET['page'] );
    $skip = 40;
    $first_entry = $skip * ( $log_page - 1 );

    $qry = "SELECT * FROM `change_log` WHERE `table_changed` LIKE '%{$_POST['s']}%' OR `item` LIKE '%{$_POST['s']}%' OR `user` LIKE '%{$_POST['s']}%' ORDER BY `id` DESC ";

    foreach ( $db->query( $qry ) AS $log )
    {
        $backups = $db->query( "SELECT `delete_id` FROM `deletes` WHERE `delete_reference` = '" . $log['id'] . "' LIMIT 1" );
        $backups = $backups->fetch();

        $type = "";
        $trClass = "";
        if ( $log['change_type'] == 0 )
        {
            $type = '<i class="icon-remove-sign" title="Deleted"></i> ';
            $trClass = 'error';
        }
        if ( $log['change_type'] == 1 )
        {
            $type = '<i class="icon-pencil" title="Modified"></i> ';
            $trClass = 'warning';
        }
        if ( $log['change_type'] == 2 )
        {
            $type = '<i class="icon-plus-sign" title="Added"></i> ';
            $trClass = 'success';
        }
        if ( $log['change_type'] == 3 )
        {
            $type = '<i class="icon-print" title="Exported"></i> ';
            $trClass = 'info';
        }
        if ( $log['change_type'] == 4 )
        {
            $type = '<i class="icon-repeat" title="Restored"></i> ';
        }

        echo '
                    <tr class="' . $trClass . '">
                        <td>' . $type . $log['table_changed'] . '</td>
                        <td>' . $log['item'] . '</td>
                        <td>' . $log['id'] . '</td>
                        <td>' . $log['user'] . ( ua() >= 100 ? ' (' . $log['ip'] . ')' : '' ) . '</td>
                        <td>' . ( strstr( $log['date'], " " ) || empty( $log['date'] ) ? $log['date'] : date( "d/m/Y H:i:s", $log['date'] ) ) . '</td>
                        <td>' . ( ! empty( $backups ) && ua() >= 100 ? '<a href="index.php?s=admin&p=logs&restore=' . $backups['delete_id'] . '" class="btn btn-mini btn-primary"><i class="icon-repeat icon-white"></i> Restore</a>' : '' ) . '</td>
                    </tr>
                ';
    }
