<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
	
	$data = $db->query( "SELECT * FROM `fairy_rings` WHERE `code` = '{$_GET['code']}' LIMIT 1" );
	$data = $data->fetch();
?>
<h4>
	Fairyrings
</h4>
<form class="form-horizontal" action="index.php?s=fairyrings&p=list" method="post">
	<input type="hidden" name="edit_value" value="<?php echo $_GET['code']; ?>" />
    <input type="hidden" name="new_entry" value="true" />
    <div class="control-group">
        <div class="control-label" for="code">Code</div>
        <div class="controls">
            <input type="text" name="code" id="code" value="<?php echo $data['code']; ?>">
        </div>
    </div>
    <div class="control-group">
        <div class="control-label" for="location">Location</div>
        <div class="controls">
            <input type="text" name="location" id="location" value="<?php echo $data['location']; ?>">
        </div>
    </div>
    <div class="control-group">
        <div class="control-label" for="coords">Coordinates</div>
        <div class="controls">
            <input type="text" name="coords" id="coords" value="<?php echo $data['coords']; ?>">
        </div>
    </div>

    <div class="control-group">
        <div class="control-actions">
            <button type="submit" class="btn btn-primary">Save changes</button>
            <button type="reset" class="btn">Reset</button>
        </div>
    </div>
</form>