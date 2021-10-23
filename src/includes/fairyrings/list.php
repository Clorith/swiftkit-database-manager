<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<?php

	if (isset($_GET['del']))
	{
		$qry = "
		    DELETE FROM
		        `fairy_rings`
            WHERE
                `code` = " . $db->quote( $_GET['del'] ) . "
            LIMIT 1
        ";

        $del_id = deleteLog( 'fairy_rings', 'code', $_GET['del'] );

		$db->query( $qry );

        $log_id = logging( 'Fairy Rings', '0', $_GET['del'] );

        deleteLogReference( $del_id, $log_id );
		
		echo '<div class="alert alert-success">You have deleted the fairy ring code <strong>' . $_GET['del'] . '</strong></div>';
	}

	if (isset($_POST['edit_value']))
	{
		$qry = "
		    UPDATE
		        `fairy_rings`
            SET
                `code` = " . $db->quote( $_POST['code'] ) . ",
                `location` = " . $db->quote( $_POST['location'] ) . ",
                `coords` = " . $db->quote( $_POST['coords'] ) . "
            WHERE
                `code` = " . $db->quote( $_POST['edit_value'] ) . "
            LIMIT 1
        ";
		
		$db->query( $qry );
		
		logging( 'Fairy Rings', '1', $_POST['code'], $_POST['edit_value'] );
		
		echo '<div class="alert alert-success">You have edited the fairy ring code <strong>' . $_POST['code'] . '</strong></div>';
	}
	if (isset($_POST['new_entry']))
	{
		$qry = "
		    INSERT INTO
		        `fairy_rings`
		            (`code`,`location`,`coords`)
            VALUES(
                " . $db->quote( $_POST['code'] ) . ",
                " . $db->quote( $_POST['location'] ) . ",
                " . $db->quote( $_POST['coords'] ) . "
            )
        ";
		
		$db->query($qry);
		
		logging( 'Fairy Rings', '2', $_POST['code'], $db->lastInsertId() );
		
		echo '<div class="alert alert-success">You added the fairy ring code <strong>' . $_POST['code'] . '</strong></div>';
	}
?>
<h4 class="pull-left">
	Fairyrings
</h4>
<div class="pull-right">
	<a href="#new" tole="button" class="btn btn-success" title="Add new entry" data-toggle="modal">
		New entry
	</a>
</div>
<table class="table table-striped">
	<thead>
		<tr>
			<th>
				Code
			</th>
			<th>
				Location
			</th>
			<th>
				Action
			</th>
		</tr>
	</thead>
	
	<tbody>
		<?php
			//	Write a list of items for this skill
			//	We use the $fields array form earlier to go through the fields we want ot actually display (we try to avoid showing the ID, we use that for the link only)
			
			foreach ($db->query( "SELECT * FROM `fairy_rings`" ) AS $row)
			{
				echo '<tr parent_val="' . $row['code'] . '">
					<td>
						<a href="#edit" data-toggle="modal" class="update_fairyring" name="' . $row['code'] .'">' . $row['code'] . '</a>
					</td>
					<td>
						' . $row['location'] . '
					</td>
					<td>
						<a href="index.php?s=fairyrings&p=list&del=' . $row['code'] . '" class="btn btn-danger do-confirm">Delete</a>
					</td>
				</tr>';
			}
		?>
	</tbody>
</table>

<div class="modal hide fade" id="new" tabindex="-1" role="dialog" aria-labelledby="newLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="newLabel">Add fairyring</h3>
    </div>

    <div class="modal-body">
        <form action="index.php?s=fairyrings&p=list" class="form-horizontal" id="newEntry" method="post">
            <input type="hidden" name="new_entry" value="true" />
            <div class="control-group">
                <div class="control-label" for="code">Code</div>
                <div class="controls">
                    <input type="text" name="code" id="code">
                </div>
            </div>
            <div class="control-group">
                <div class="control-label" for="location">Location</div>
                <div class="controls">
                    <input type="text" name="location" id="location" class="location">
                </div>
            </div>
            <div class="control-group">
                <div class="control-label" for="coords">Coordinates</div>
                <div class="controls">
                    <div class="input-append">
                        <input type="text" name="coords" id="coords" class="map-coords">
                        <button class="btn open-map-position" type="button" href="#map-position" data-toggle="modal"><i class="icon-globe"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="add_fairyring">Add location</button>
    </div>
</div>

<div class="modal hide fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="editLabel">Edit fairyring</h3>
    </div>

    <div class="modal-body">
        <form action="index.php?s=fairyrings&p=list" class="form-horizontal" method="post">
            <input type="hidden" name="edit_entry" value="true" />
            <input type="hidden" name="edit_value" id="edit_value" value="" />
            <div class="control-group">
                <div class="control-label" for="edit-code">Code</div>
                <div class="controls">
                    <input type="text" name="code" id="edit-code">
                </div>
            </div>
            <div class="control-group">
                <div class="control-label" for="edit-location">Location</div>
                <div class="controls">
                    <input type="text" name="location" id="edit-location" class="location">
                </div>
            </div>
            <div class="control-group">
                <div class="control-label" for="edit-coords">Coordinates</div>
                <div class="controls">
                    <div class="input-append">
                        <input type="text" name="coords" id="edit-coords" class="map-coords">
                        <button class="btn open-map-position" type="button" href="#map-position" data-toggle="modal"><i class="icon-globe"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="edit_fairyring" data-dismiss="modal">Edit location</button>
    </div>
</div>

<div class="modal container hide fade" id="map-position">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Location</h3>
    </div>

    <div class="modal-body">
        <input type="text" name="location_name" id="location_name" placeholder="Location Name">
        <div id="map">
            Loading RuneScape World Map
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="map_location" data-dismiss="modal">Set location</button>
    </div>
</div>