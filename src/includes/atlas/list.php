<?php
    if (!defined('SKDBM')) { die('Define missing'); }
?>
<?php

    $del = array();
    
    if ( isset( $_GET['del'] ) )
    {
        $qry = "DELETE FROM `atlas_{$_GET['atlas']}` WHERE `{$_GET['field']}` = '{$_GET['del']}' LIMIT 1";
    
        $del_id = deleteLog( 'atlas_' . $_GET['atlas'], $_GET['field'], $_GET['del'] );
    
        $db->query($qry);
    
        $log_id = logging( 'Atlas ' . $_GET['atlas'], '0', $_GET['del'] );
    
        deleteLogReference( $del_id, $log_id );
    
        echo '<div class="alert alert-success">You have deleted <strong>' . $_GET['del'] . '</strong> from the database.</div>';
    }
    if ( isset( $_POST['edit_key'] ) )
    {
        $qry = "UPDATE `atlas_{$_GET['atlas']}` SET ";
        foreach ( $_POST AS $key => $val )
        {
            if ( in_array( $key, array( 'edit_key', 'edit_value' ) ) )
                continue;
    
            $qry .= "`{$key}` = '{$val}',";
        }
        $qry = substr( $qry, 0, -1 );
    
        $qry .= " WHERE `{$_POST['edit_key']}` = '{$_POST['edit_value']}' LIMIT 1";
    
        $db->query( $qry );
    
        logging( 'Atlas ' . $_GET['atlas'], '1', $_POST['edit_value'] );
    
        echo '<div class="alert alert-success">You have updated <strong>' . $_POST['edit_value'] . '</strong>.</div>';
    }
    if ( isset( $_POST['new_entry'] ) )
    {
        $qry = "INSERT INTO `atlas_{$_GET['atlas']}` (";
        $vals = "";
        foreach ( $_POST AS $key => $val )
        {
            if ( in_array( $key, array( 'new_entry' ) ) )
                continue;
    
            $qry .= "`{$key}`,";
            $vals .= "'{$val}',";
        }
    
        // Remove the last comma seperator from both variables, would muck up and make MySQL angry
        $qry = substr( $qry, 0, -1 );
        $vals = substr( $vals, 0, -1 );
    
        $qry .= ") VALUES(" . $vals . ")";

        $_POST = array_values( $_POST );
    
        $db->query( $qry );
    
        logging( 'Atlas ' . $_GET['atlas'], '2', $_POST[0] );
    
        echo '<div class="alert alert-success">You have added <strong>' . $_POST[0] . '</strong></div>';
    }
?>
<div id="notifications">
    <!--
        This area is empty until we do an ajax update of a atlas, it will then be temporarily populated by a notification message (which will, of course, disapear after a second or two)
    -->
</div>
<h4 class="pull-left">
    <span id="atlas_name"><?php echo ucfirst( $_GET['atlas'] ); ?></span>
</h4>
<div class="pull-right">
    <a href="#dialog" title="Add new entry" role="button" class="btn btn-success" data-toggle="modal">
        New entry
    </a>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <?php
        //	Generate table headers based on the selected atlass fields
        //	Also write a list of fields that we wish to output data for when fetching rows

        $fields = array();


        foreach ($db->query( "SHOW FULL COLUMNS FROM `atlas_{$_GET['atlas']}`" ) AS $field)
        {
            if ($field['Key'] == 'PRI')
                continue;

            echo '<th>' . $field['Field'] . '</th>';
            $fields[] = $field['Field'];
        }

        ?>
    </tr>
    </thead>

    <tbody>
    <?php
    //	Write a list of items for this atlas
    //	We use the $fields array form earlier to go through the fields we want ot actually display (we try to avoid showing the ID, we use that for the link only)

    foreach ($db->query( "SELECT * FROM `atlas_{$_GET['atlas']}`" ) AS $row)
    {
        $rows = "";

        $first = true;
        foreach ($fields AS $num => $field)
        {
            $content = "";

            if ($first) {
                $del['column'] = $row[$field];
                $del['field'] = $field;
                $content .= '<a href="#edit" data-toggle="modal" class="edit-atlas" rel="edit">';
            }

            $content .= (! $first ? '<span>' . $row[$field] . '</span>' : $row[$field] );

            if ($first)
                $content .= '</a>';

            $rows .= '<td class="atlas-edit" column="' . $field . '" value="' . $row[$field] . '">';
            $rows .= $content;
            $rows .= '</td>';

            if ($first)
                $first = false;
        }

        $rows .= '<td>
                    <a href="index.php?s=atlas&p=list&atlas=' . $_GET['atlas'] . '&del=' . $del['column'] . '&field=' . $del['field'] . '" class="do-confirm btn btn-danger">Delete</a>
                </td>';

        echo '<tr parent_val="' . $del['column'] . '" parent="' . $del['field'] . '">';
        echo $rows;
        echo '</tr>';
    }
    ?>
    </tbody>
</table>

<div class="modal hide fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="newEntryLabel" aria-hidden="true">
    <div class="modal-header">
        <h3 id="newEntryLabel">New Entry</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" action="index.php?s=atlas&p=list&atlas=<?php echo $_GET['atlas']; ?>" id="add_atlas_form" method="post">
            <input type="hidden" name="new_entry" value="true" />
            <?php
            //	Generate table headers based on the selected atlass fields
            //	Also write a list of fields that we wish to output data for when fetching rows

            $fields = array();
            $values = array();

            foreach ($db->query( "SHOW FULL COLUMNS FROM `atlas_{$_GET['atlas']}`" ) AS $fielder)
            {
                if ($fielder['Key'] == 'PRI')
                    continue;

                $type = explode( "(", $fielder['Type'] );
                $types[$fielder['Field']] = $type[0];
                $comment[] = $fielder['Comment'];

                $values[$fielder['Field']] = str_replace( ")", "", $type[1] );
            }

            $count = 0;
            foreach ($types AS $field => $type)
            {
                echo '
                    <div class="control-group">
                        <label class="control-label" for="' . $field . '">' . ucfirst( str_replace( "_", " ", $field ) ) . '</label>
                        <div class="controls">
                            <div class="input-append">';

                if ($type == 'int' || $type == 'double')
                    echo '<input class="' . $field . '" name="' . $field . '" id="' . $field . '" type="number" step="any" />';
                elseif ( $type == 'varchar' && $values[$field] <= 150 )
                    echo '<input class="' . $field . '" name="' . $field . '" id="' . $field . '" type="text" />';
                else
                    echo '<textarea class="' . $field . '" name="' . $field . '" id="' . $field . '"></textarea>';

                if ( $field == 'location' )
                    echo '<button class="btn open-map-position" type="button" href="#map-position" data-toggle="modal"><i class="icon-globe"></i></button>';

                echo '</div>';

                if ( ! empty( $comment[$count] ) )
                    echo '<span class="help-block">' . $comment[$count] . '</span>';

                echo '
                        </div>
                    </div>';

                $count++;
            }
            ?>
            </table>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary form-submit" name="#add_atlas_form" id="add_atlas" data-dismiss="modal" aria-hidden="true">Add</button>
    </div>
</div>
<div class="modal hide fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-header">
        <h3 id="editLabel">Edit entry</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" action="index.php?s=atlas&p=list&atlas=<?php echo $_GET['atlas']; ?>" id="atlas_update" method="post">
            <input type="hidden" id="edit_key" name="edit_key" value="" />
            <input type="hidden" id="edit_value" name="edit_value" value="" />
            <?php
            //	Generate table headers based on the selected atlass fields
            //	Also write a list of fields that we wish to output data for when fetching rows

            $count = 0;
            foreach ($types AS $field => $type)
            {
                echo '
                    <div class="control-group">
                        <label class="control-label" for="' . $field . '">' . ucfirst( str_replace( "_", " ", $field ) ) . '</label>
                        <div class="controls">
                            <div class="input-append">';

                if ($type == 'int' || $type == 'double')
                    echo '<input class="' . $field . '" name="' . $field . '" id="e_' . $field . '" type="number" step="any" />';
                elseif ( $type == 'varchar' && $values[$field] <= 150 )
                    echo '<input class="' . $field . '" name="' . $field . '" id="e_' . $field . '" type="text" />';
                else
                    echo '<textarea class="' . $field . '" name="' . $field . '" id="e_' . $field . '"></textarea>';

                if ( $field == 'location' )
                    echo '<button class="btn open-map-position" type="button" href="#map-position" data-toggle="modal"><i class="icon-globe"></i></button>';

                echo '</div>';

                if ( ! empty( $comment[$count] ) )
                    echo '<span class="help-block">' . $comment[$count] . '</span>';

                echo '
                        </div>
                    </div>';

                $count++;
            }
            ?>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary form-submit" name="#atlas_update" id="atlas_update_button" data-dismiss="modal">Save changes</button>
    </div>
</div>

<div class="modal container hide fade" id="map-position">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Location</h3>
    </div>

    <div class="modal-body">
        <input type="hidden" name="location_name" id="location_name" placeholder="Location Name" value="">
        <div id="map">
            Loading RuneScape World Map
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="map_location" data-dismiss="modal">Set location</button>
    </div>
</div>