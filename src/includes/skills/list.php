<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<?php

    $del = array();

	if ( isset( $_GET['del'] ) )
	{
		$qry = "DELETE FROM `calc_" . $_GET['skill'] . "` WHERE `" . $_GET['field'] . "` = " . $db->quote( $_GET['del'] ) . " LIMIT 1";

        $del_id = deleteLog( 'calc_' . $_GET['skill'], $_GET['field'], $_GET['del'] );
		
		$db->query( $qry );
		
		$log_id = logging( $_GET['skill'] . ' Calc', '0', $_GET['del'] );

        deleteLogReference( $del_id, $log_id );
		
		echo '<div class="alert alert-success">You have deleted <strong>' . $_GET['del'] . '</strong> from the database.</div>';
	}
	if ( isset( $_POST['edit_key'] ) )
	{
		$qry = "UPDATE `calc_" . $_GET['skill'] . "` SET ";
		foreach ( $_POST AS $key => $val )
		{
			if ( in_array( $key, array( 'edit_key', 'edit_value' ) ) )
				continue;
				
			$qry .= "`" . $key . "` = " . $db->quote( $val ) . ",";
		}
		$qry = substr( $qry, 0, -1 );
		
		$qry .= " WHERE `" . $_POST['edit_key'] . "` = '" . $_POST['edit_value'] . "' LIMIT 1";
		
		$db->query( $qry );
		
		logging( $_GET['skill'] . ' Calc', '1', $_POST['edit_value'], $_POST['edit_value'] );
		
		echo '<div class="alert alert-success">You have updated <strong>' . $_POST['edit_value'] . '</strong>.</div>';
	}
	if ( isset( $_GET['new_entry'] ) )
	{
		$qry = "INSERT INTO `calc_" . $_GET['skill'] . "` (";
		$vals = "";
		foreach ( $_POST AS $key => $val )
		{
			$qry .= "`" . $key . "`,";
			$vals .= $db->quote( $val ) . ",";
		}
		
		// Remove the last comma seperator from both variables, would muck up and make MySQL angry
		$qry = substr( $qry, 0, -1 );
		$vals = substr( $vals, 0, -1 );
		
		$qry .= ") VALUES(" . $vals . ")";
		
		$db->query( $qry );

        //  Order the POST values so that primary item becomes 0, instead of the column name, ensures we always log the right thing even if the primary column has different names for various skills
        $_POST = array_values( $_POST );

		logging( $_GET['skill'] . ' Calc', '2', $_POST[0], $db->lastInsertId() );
		
		echo '<div class="alert alert-success">You have added <strong>' . $_POST[0] . '</strong></div>';
	}
?>
<div id="notifications">
    <!--
        This area is empty until we do an ajax update of a skill, it will then be temporarily populated by a notification message (which will, of course, disapear after a second or two)
    -->
</div>
<h4 class="pull-left">
	<img src="images/skillicons/<?php echo $_GET['skill']; ?>.png" width="30" />
	<span id="skill_name"><?php echo ucfirst( $_GET['skill'] ); ?></span>
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
				//	Generate table headers based on the selected skills fields
				//	Also write a list of fields that we wish to output data for when fetching rows
				
				$fields = array();
				
				
				foreach ($db->query( "SHOW FULL COLUMNS FROM `calc_{$_GET['skill']}`" ) AS $field)
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
			//	Write a list of items for this skill
			//	We use the $fields array form earlier to go through the fields we want ot actually display (we try to avoid showing the ID, we use that for the link only)
			
			foreach ( $db->query( "SELECT * FROM calc_" . $_GET['skill'] . " s ORDER BY s.level ASC " ) AS $row )
			{
                $rows = "";

				$first = true;
				foreach ( $fields AS $num => $field )
				{
                    $content = "";

                    if ( $first ) {
                        $del['column'] = $row[$field];
                        $del['field'] = $field;
                        $content .= '<a href="#edit" data-toggle="modal" class="edit-skill" rel="edit">';
                    }

                    $content .= ( ! $first ? '<span>' . $row[$field] . '</span>' : $row[$field] );

                    if ( $first )
                        $content .= '</a>';

                    $rows .= '<td class="skill-edit" column="' . $field . '" value="' . $row[$field] . '">';
                    $rows .= $content;
                    $rows .= '</td>';
					
					if ( $first )
						$first = false;
				}

                $rows .= '<td>
                    <a href="index.php?s=skills&p=list&skill=' . $_GET['skill'] . '&del=' . $del['column'] . '&field=' . $del['field'] . '" class="do-confirm btn btn-danger">Delete</a>
                </td>';

                echo '<tr parent_val="' . $row['id'] . '" parent="id">';
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
        <form class="form-horizontal" action="index.php?s=skills&p=list&skill=<?php echo $_GET['skill']; ?>&new_entry=true" id="add_skill_form" method="post">
                <?php
                //	Generate table headers based on the selected skills fields
                //	Also write a list of fields that we wish to output data for when fetching rows

                $fields = array();
                $values = array();

                foreach ( $db->query( "SHOW FULL COLUMNS FROM `calc_" . $_GET['skill'] . "`" ) AS $fielder )
                {
                    if ( $fielder['Key'] == 'PRI' )
                        continue;

                    $type = explode( "(", $fielder['Type'] );
                    $types[$fielder['Field']] = $type[0];
                    $comment[] = $fielder['Comment'];

                    $values[$fielder['Field']] = str_replace( ")", "", $type[1] );
                }

                $count = 0;
                foreach ( $types AS $field => $type )
                {
                    echo '
                    <div class="control-group">
                        <label class="control-label" for="' . $field . '">' . ucfirst( str_replace( "_", " ", $field ) ) . '</label>
                        <div class="controls">';

                    if ($type == 'int' || $type == 'double')
                        echo '<input name="' . $field . '" id="' . $field . '" type="number" step="any" />';
                    elseif ( $type == 'varchar' && $values[$field] <= 150 )
                        echo '<input name="' . $field . '" id="' . $field . '" type="text" />';
                    else
                        echo '<textarea name="' . $field . '" id="' . $field . '"></textarea>';

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
        <button class="btn btn-primary" id="add_skill" data-dismiss="modal" aria-hidden="true">Add</button>
    </div>
</div>
<div class="modal hide fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-header">
        <h3 id="editLabel">Edit entry</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" action="index.php?s=skills&p=list&skill=<?php echo $_GET['skill']; ?>" id="skill_update" method="post">
            <input type="hidden" id="edit_key" name="edit_key" value="" />
            <input type="hidden" id="edit_value" name="edit_value" value="" />
                <?php
                //	Generate table headers based on the selected skills fields
                //	Also write a list of fields that we wish to output data for when fetching rows

                $count = 0;
                foreach ( $types AS $field => $type )
                {
                    echo '
                    <div class="control-group">
                        <label class="control-label" for="' . $field . '">' . ucfirst( str_replace( "_", " ", $field ) ) . '</label>
                        <div class="controls">';

                    if ( $type == 'int' || $type == 'double' )
                        echo '<input name="' . $field . '" id="e_' . $field . '" type="number" step="any" />';
                    elseif ( $type == 'varchar' && $values[$field] <= 150 )
                        echo '<input name="' . $field . '" id="e_' . $field . '" type="text" />';
                    else
                        echo '<textarea name="' . $field . '" id="e_' . $field . '"></textarea>';

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
        <button class="btn btn-primary" id="skill_update_button" data-dismiss="modal">Save changes</button>
    </div>
</div>