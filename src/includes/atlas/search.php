<?php
    if (!defined('SKDBM')) { die('Define missing'); }

    if ( isset( $_GET['del'] ) )
    {
        $qry = "DELETE FROM `atlas_search` WHERE `atlas_search_name` = " . $db->query( $_GET['del'] ) . " LIMIT 1";

        $del_id = deleteLog( 'atlas_search', `atlas_search_name`, $_GET['del'] );

        $db->query( $qry );

        $log_id = logging( 'Atlas Search', '0', $_GET['del'] );

        deleteLogReference( $del_id, $log_id );

        echo '<div class="alert alert-success">You have deleted <strong>' . $_GET['del'] . '</strong> from the database.</div>';
    }
    if ( isset( $_POST['new_entry'] ) )
    {
        $db->query( "
            INSERT INTO
                `atlas_search`
                  ( `atlas_search_name`, `atlas_search_location` )
            VALUES (
                " . $db->query( $_POST['atlas_search_name'] ) . ",
                " . $db->query( $_POST['atlas_search_location'] ) . "
            )
        " );

        logging( 'Atlas Search', '2', $_POST['atlas_search_name'], $db->lastInsertId() );

        echo '<div class="alert alert-success">You have added <strong>' . $_POST['atlas_search_name'] . '</strong></div>';
    }
?>
<div id="notifications">
    <!--
        This area is empty until we do an ajax update of a skill, it will then be temporarily populated by a notification message (which will, of course, disapear after a second or two)
    -->
</div>
<h4 class="pull-left">
    <img src="images/skillicons/map.gif" width="30" />
    <span id="skill_name">Atlas Search</span>
</h4>
<div class="pull-right">
    <a href="#dialog" title="Add new entry" role="button" class="btn btn-success" data-toggle="modal">
        New entry
    </a>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Location</th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($db->query( "SELECT * FROM `atlas_search`" ) AS $row)
    {
        echo '
            <tr parent_val="atlas_search_name" parent="' . $row['atlas_search_name'] . '">
                <td class="skill-edit" column="atlas_search_name" value="' . $row['atlas_search_name'] . '">
                    <a href="#edit" data-toggle="modal" class="edit" rel="edit">
                        <span>' . $row['atlas_search_location'] . '</span>
                    </a>
                </td>
                <td>
                    <a href="index.php?s=atlas&p=search&del=' . $row['atlas_search_name'] . '" class="do-confirm btn btn-danger">Delete</a>
                </td>
            </tr>
        ';
    }
    ?>
    </tbody>
</table>

<div class="modal hide fade" id="dialog" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <h3>Add entry</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" action="" id="skill_add" method="post">
            <input type="hidden" name="new_entry" value="true" />

            <div class="control-group">
                <label class="control-label" for="atlas_search_location">Location</label>
                <div class="controls">
                    <div class="input-append">
                        <input class="map-coords" name="atlas_search_location" id="atlas_search_location" type="text">
                        <button class="btn open-map-position" type="button" href="#map-position" data-toggle="modal"><i class="icon-globe"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary form-submit" name="#skill_add" data-dismiss="modal">Add location</button>
    </div>
</div>

<div class="modal hide fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <h3>Edit entry</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" action="" id="skill_update" method="post">
            <input type="hidden" id="edit_key" name="edit_key" value="" />
            <input type="hidden" id="edit_value" name="edit_value" value="" />

            <div class="control-group">
                <label class="control-label" for="e_atlas_search_location">Location</label>
                <div class="controls">
                    <div class="input-append">
                        <input class="map-coords" name="atlas_search_location" id="e_atlas_search_location" type="text">
                        <button class="btn open-map-position" type="button" href="#map-position" data-toggle="modal"><i class="icon-globe"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary form-submit" name="#skill_update" id="skill_update_button" data-dismiss="modal">Save changes</button>
    </div>
</div>

<div class="modal container hide fade" id="map-position">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Location</h3>
    </div>

    <div class="modal-body">
        <input type="hidden" name="location_name" id="location_name" value="">
        <div id="map">
            Loading RuneScape World Map
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="map_location" data-dismiss="modal">Set location</button>
    </div>
</div>