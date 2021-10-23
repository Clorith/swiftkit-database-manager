<?php
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<div class="well">
    <h3>Statistics</h3>

    <?php
    $stats['tasks'] = $db->query( "SELECT COUNT(DISTINCT `id`) AS `count` FROM `achievement_diaries`" );
    $stats['change'] = $db->query( "SELECT `id` FROM `change_log` ORDER BY `id` DESC LIMIT 1" );
    $stats['rings'] = $db->query( "SELECT COUNT(DISTINCT `code`) AS `count` FROM `fairy_rings`" );
    $stats['quests'] = $db->query( "SELECT COUNT(DISTINCT `id`) AS `count` FROM `quests`" );

    $stats['tasks'] = $stats['tasks']->fetch();
    $stats['change'] = $stats['change']->fetch();
    $stats['rings'] = $stats['rings']->fetch();
    $stats['quests'] = $stats['quests']->fetch();
    ?>

    <div class="row-fluid">
        <div class="span2">Skills <span class="badge badge-info"><?php echo count( $menus['skills'] ); ?></span></div>
        <div class="span2"><a href="index.php?s=quests&p=list">Quests</a> <span class="badge badge-info"><?php echo $stats['quests']['count']; ?></span></div>
        <div class="span2"><a href="index.php?s=tasks&p=list">Tasks</a> <span class="badge badge-info"><?php echo $stats['tasks']['count']; ?></span></div>
        <div class="span2"><a href="https://skdbm.clorith.space/index.php?s=fairyrings&p=list">Fairy rings</a> <span class="badge badge-info"><?php echo $stats['rings']['count']; ?></span></div>
        <div class="span2"><a href="https://skdbm.clorith.space/index.php?s=admin&p=logs">Changes</a> <span class="badge badge-info"><?php echo $stats['change']['id']; ?></span></div>
    </div>
</div>

<div class="well">
    <h3>Active users <small>(last 15 minutes)</small></h3>
    <div class="row-fluid">
        <?php
            foreach ( $db->query( "SELECT `username` FROM `users` WHERE `last_action` >= '" . ( time() - 900 ) . "'" ) AS $active )
            {
                echo '<span class="span2">' . ( strtolower( $active['username'] ) == strtolower( $_COOKIE['user_name'] ) ? '<strong>' . $active['username'] . '</strong>' : $active['username'] ) . '</span>';
            }
        ?>
    </div>
</div>

<div class="well">
    <h3>Latest changes</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Table</th>
            <th>Item</th>
            <th>Change ID</th>
            <th>User</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $qry = "SELECT * FROM `change_log` ORDER BY `id` DESC LIMIT 5";

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
        ?>
        </tbody>
    </table>
</div>
