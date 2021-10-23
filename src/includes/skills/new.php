<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<h4>
	<img src="images/skillicons/<?php echo $_GET['skill']; ?>.png" width="30" />
	<?php echo ucfirst( $_GET['skill'] ); ?>
</h4>
<form action="index.php?s=skills&p=list&skill=<?php echo $_GET['skill']; ?>" method="post">
	<input type="hidden" name="new_entry" value="true" />
	<table class="col_12">
		<?php
			//	Generate table headers based on the selected skills fields
			//	Also write a list of fields that we wish to output data for when fetching rows
			
			$fields = array();
			
			foreach ($db->query( "DESCRIBE `calc_{$_GET['skill']}`" ) AS $fielder)
			{
				if ($fielder['Key'] == 'PRI')
					continue;
				
				$type = explode("(", $fielder['Type']);
				$types[$fielder['Field']] = $type[0];

                $values[$fielder['Field']] = str_replace( ")", "", $type[1] );
			}

			foreach ($types AS $field => $type)
			{
				echo '
				<tr>
					<td>' . ucfirst( str_replace( "_", " ", $field ) ) . '</td>
					<td>';

                    if ($type == 'int' || $type == 'double')
                        echo '<input name="' . $field . '" id="' . $field . '" type="number" step="any" />';
                    elseif ( $type == 'varchar' && $values[$field] <= 150 )
                        echo '<input name="' . $field . '" id="' . $field . '" type="text" />';
                    else
                        echo '<textarea name="' . $field . '" id="' . $field . '"></textarea>';
					
				echo '
					</td>
				</tr>';
			}
		?>
		<tr>
			<td colspan="2">
				<input type="submit" class="green" value="Add" />
			</td>
		</tr>
	</table>
</form>