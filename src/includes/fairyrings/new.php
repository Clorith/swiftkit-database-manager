<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<h4>
	Fairyrings
</h4>
<form action="index.php?s=fairyrings&p=list" class="form-horizontal" method="post">
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
            <input type="text" name="location" id="location">
        </div>
    </div>
    <div class="control-group">
        <div class="control-label" for="coords">Coordinates</div>
        <div class="controls">
            <input type="text" name="coords" id="coords">
        </div>
    </div>

    <div class="control-group">
        <div class="control-actions">
            <button type="submit" class="btn btn-primary">Add location</button>
            <button type="reset" class="btn">Reset</button>
        </div>
    </div>
</form>