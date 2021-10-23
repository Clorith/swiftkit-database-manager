<?php
    if (!defined('SKDBM')) { die('Define missing'); }

    if ( isset( $_GET['restore'] ) && ua() >= 100 )
    {
        //  Restoring an old entry, start by fetching the data we have logged about it
        $restore = $db->query( "SELECT * FROM `deletes` WHERE `delete_id` = '{$_GET['restore']}' LIMIT 1" );
        $restore = $restore->fetch();

        //  We stored all our data in a json string so it would look neat and be easy to grab back, let's do that
        $entries = json_decode( $restore['delete_entry'] );

        //  Next build an array of all the columns the entry expects
        $fields = array();
        foreach ($db->query( "SHOW FULL COLUMNS FROM `" . $restore['delete_table'] . "`" ) AS $field)
        {
            if ($field['Key'] == 'PRI')
                continue;

            $fields[] = $field['Field'];
        }

        //  PRedefine the variables that will hold our SQL information
        $item = "";
        $value = "";

        //  Start looping through the coulmns
        for ( $i = 0; $i < count( $fields ); $i++ )
        {
            // Set field variable, easier to work with
            $field = $fields[$i];

            $item .= "`" . $field . "`,";

            if ( ! isset( $entries->$field ) || empty( $entries->$field ) )
                $value .= "'',";
            else
                $value .= "'" . $entries->$field . "',";
        }

        //  Done looping through all of that, let's finish building the query and insert this bad boy!
        //  First things first, remove trailing commas!
        $item = substr( $item, 0, -1 );
        $value = substr( $value, 0, -1 );

        $sql = "
            INSERT INTO
                `" . $restore['delete_table'] . "`
                    (" . $item . ")
            VALUES
                (" . $value . ")
        ";

        $db->query( $sql );

        logging( 'Restore', '4', $restore['delete_value'] );

        echo '<div class="alert alert-success">You have restored the item, <strong>' . $restore['delete_value'] . '</strong>, to its original location.</div>';
    }

    $log_page = ( !isset( $_GET['page'] ) ? 1 : $_GET['page'] );
    $skip = ( 40 * $log_page );
    $first_entry = $skip * ( $log_page - 1 );
?>
<div class="span12">
    <form action="index.php" method="get" class="pull-right">
        <div class="input-append">
            <input type="hidden" name="s" value="admin">
            <input type="hidden" name="p" value="logs">
            <input type="text" name="find" placeholder="Search..." value="<?php echo ( isset( $_GET['find'] ) ? $_GET['find'] : '' ); ?>">
            <button type="submit" class="btn">Search</button>
        </div>
    </form>
</div>
<table class="table table-striped logList" data-page="<?php echo $log_page; ?>" data-string="<?php echo ( isset( $_GET['find'] ) ? $_GET['find'] : '%' ); ?>">
    <thead>
        <tr>
            <th style="width:">Table</th>
            <th>Item</th>
            <th>Change ID</th>
            <th>User</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="logList">
    <?php
        if ( ! isset( $_GET['find'] ) )
            $qry = "SELECT * FROM `change_log` ORDER BY `id` DESC LIMIT {$first_entry},{$skip}";
        else
            $qry = "SELECT * FROM `change_log` WHERE `table_changed` LIKE '%{$_GET['find']}%' OR `item` LIKE '%{$_GET['find']}%' OR `user` LIKE '%{$_GET['find']}%' ORDER BY `id` DESC LIMIT {$first_entry},{$skip} ";

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

            //  Write URL for page that was logged
            $url = $log['entry_page'];

            echo '
                <tr class="logListEntry ' . $trClass . '">
                    <td>' . $type . $log['table_changed'] . '</td>
                    <td>' . $log['item'] . '</td>
                    <td>' . $log['id'] . '</td>
                    <td title="' . ( ua() >= 100 ? 'IP: ' . $log['ip'] : '' ) . '">' . $log['user'] . '</td>
                    <td>' . ( strstr( $log['date'], " " ) || empty( $log['date'] ) ? $log['date'] : date( "d/m/Y H:i:s", $log['date'] ) ) . '</td>
                    <td>
                        ' . ( ! empty( $url ) && $log['change_type'] < 3 && $log['change_type'] > 0 ? '<a href="' . $url . '#id=' . $log['entry_id'] . '" class="btn btn-mini"><i class="icon-eye-open"></i> View</a>' : '' ) . '
                        ' . ( ! empty( $backups ) && ua() >= 100 ? '<a href="index.php?s=admin&p=logs&restore=' . $backups['delete_id'] . '" class="btn btn-mini btn-primary"><i class="icon-repeat icon-white"></i> Restore</a>' : '' ) . '
                    </td>
                </tr>
            ';
        }
    ?>
    </tbody>
</table>

<?php
if (! isset( $_POST['s'] ) )
{
    ?>
    <div class="pagination pagination-centered">
        <ul>
            <li class="<?php echo ( ( $log_page - 1 ) <= 1 ? 'disabled' : '' ); ?>"><a href="index.php?s=admin&amp;p=logs&amp;page=<?php echo ( ( $log_page - 1 ) >= 1 ? ( $log_page - 1 ) : '1' ) . ( isset( $_GET['find'] ) ? '&amp;find=' . $_GET['find'] : '' ); ?>">&laquo;</a></li>
            <?php
            //  Quick query to grab a count of how many entries there are, to get the pagination going
            $count = $db->query( "SELECT COUNT(DISTINCT id) AS `entries` FROM `change_log`" . ( isset( $_GET['find'] ) ? " WHERE `table_changed` LIKE '%{$_GET['find']}%' OR `item` LIKE '%{$_GET['find']}%' OR `user` LIKE '%{$_GET['find']}%'" : '' ) );
            $count = $count->fetch();
            $pages_total = ceil( $count['entries'] / $skip );
            for ( $i = 1; $i < $pages_total; $i++ )
            {
                //  This is a log page, it could get massive, let's do some "nice" paginations and only show 5 before and 5 after the current page if our criterias are met
                if ( $pages_total >= 25 && ( ( $log_page - 5 ) <= $i ) && ( ( $log_page + 5 ) >= $i ) )
                    echo '
                        <li class="' . ( $i == $log_page ? 'active' : '' ) . '">
                            <a href="index.php?s=admin&p=logs&page=' . $i . '">' . $i . '</a>
                        </li>
                    ';
                elseif ( $pages_total < 25 || ( ( ( $log_page - 5 ) >= $i ) && ( ( $log_page + 5 ) <= $i ) ) )
                    echo '
                        <li class="' . ( $i == $log_page ? 'active' : '' ) . '">
                            <a href="index.php?s=admin&p=logs&page=' . $i . '">' . $i . '</a>
                        </li>
                    ';

            }
            ?>
            <li class="<?php echo ( ( $log_page + 1 ) >= $pages_total ? ' disabled' : '' ); ?>"><a class="nextPage" href="index.php?s=admin&amp;p=logs&amp;page=<?php echo ( ( $log_page + 1 ) < $pages_total ? ( $log_page + 1 ) : $log_page ) . ( isset( $_GET['find'] ) ? '&amp;find=' . $_GET['find'] : '' ); ?>">&raquo;</a></li>
        </ul>
    </div>
<?php
}
?>