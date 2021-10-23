<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<?php

	//	Remove the "constants" from our $_GET as they will muck up the rest of the code (aka, let�s jsut grab the last GET element)
	//	No pre-existing function for this as we need both the value, and the key
	$find = array(
		'key'   => '',
		'value' => ''
	);
	$constants = array('s','p','skill');
	foreach ($_GET AS $key => $value)
	{
		if (!in_array($key, $constants))
		{
			$find['key'] = addslashes( $key );
			$find['value'] = addslashes( $value );
		}
	}

?>
<h4>
	<img src="images/skillicons/<?php echo $_GET['skill']; ?>.png" width="30" />
	<?php echo ucfirst( $_GET['skill'] ); ?>
</h4>
<form action="index.php?s=skills&p=list&skill=<?php echo $_GET['skill']; ?>" method="post">
	<input type="hidden" name="edit_key" value="<?php echo $find['key']; ?>" />
	<input type="hidden" name="edit_value" value="<?php echo $find['value']; ?>" />
	<table class="col_12">
		<?php
			//	Generate table headers based on the selected skills fields
			//	Also write a list of fields that we wish to output data for when fetching rows

			foreach ($db->query( "DESCRIBE `calc_{$_GET['skill']}`" ) AS $fielder)
			{
				if ($fielder['Key'] == 'PRI')
					continue;

				$type = explode("(", $fielder['Type']);
				$types[$fielder['Field']] = $type[0];

                $values[$fielder['Field']] = str_replace( ")", "", $type[1] );
			}

			foreach ($db->query( "SELECT * FROM `calc_{$_GET['skill']}` WHERE `{$find['key']}` = '{$find['value']}'" ) AS $row)
			{
				$first = true;
				foreach ($types AS $field => $type)
				{
					echo '
					<tr>
						<td>' . ucfirst( str_replace( "_", " ", $field ) ) . '</td>
						<td>';

                        if ($type == 'int' || $type == 'double')
                            echo '<input name="' . $field . '" id="' . $field . '" value="' . $row[$field] . '" type="number" step="any" />';
                        elseif ( $type == 'varchar' && $values[$field] <= 150 )
                            echo '<input name="' . $field . '" id="' . $field . '" value="' . $row[$field] . '" type="text" />';
                        else
                            echo '<textarea name="' . $field . '" id="' . $field . '">' . $row[$field] . '</textarea>';

					echo '
						</td>
					</tr>';
				}
			}
		?>
		<tr>
			<td colspan="2">
				<input type="submit" class="green" value="Update" />
			</td>
		</tr>
	</table>
</form>