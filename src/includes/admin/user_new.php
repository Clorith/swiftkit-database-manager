<?php
    if (!defined('SKDBM')) { die('Define missing'); }
?>
<h4>
    New system user
</h4>
<form action="index.php?s=admin&p=users" method="post" class="form-horizontal">
    <div class="control-group">
        <div class="control-label" for="new_user">Username</div>
        <div class="controls">
            <input type="text" name="new_user" id="new_user">
        </div>
    </div>

    <div class="control-group">
        <div class="control-label" for="new_pass">Password</div>
        <div class="controls">
            <input type="password" name="new_pass" id="new_pass">
        </div>
    </div>

    <div class="control-group">
        <div class="control-label" for="new_access">Access</div>
        <div class="controls">
            <select name="new_access" id="new_access">
                <option value="0">Disabled</option>
                <option value="1">Regular user</option>
                <option value="100">Administrator</option>
            </select>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add user</button>
    </div>
</form>