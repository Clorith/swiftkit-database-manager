<?php
    if (!defined('SKDBM')) { die('Define missing'); }

    $data = $db->query( "SELECT `username`,`access` FROM `users` WHERE `uid` = '{$_GET['id']}' LIMIT 1" );
    $data = $data->fetch();
?>
<h4>
    Edit system user
</h4>
<form action="index.php?s=admin&p=users" method="post">
    <div class="control-group">
        <div class="control-label" for="edit_user">Username</div>
        <div class="controls">
            <input type="text" name="edit_user" id="edit_user" value="<?php echo $data['username']; ?>">
        </div>
    </div>

    <div class="control-group">
        <div class="control-label" for="edit_pass">Password</div>
        <div class="controls">
            <input type="password" name="edit_pass" id="edit_pass">
        </div>
    </div>

    <div class="control-group">
        <div class="control-label" for="edit_access">Access</div>
        <div class="controls">
            <select name="edit_access" id="edit_access">
                <option value="0"<?php if ( $data['access'] == '0' ) echo ' SELECTED'; ?>>Disabled</option>
                <option value="1"<?php if ( $data['access'] == '1' ) echo ' SELECTED'; ?>>Regular user</option>
                <option value="100"<?php if ( $data['access'] == '100' ) echo ' SELECTED'; ?>>Administrator</option>
            </select>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Edit user</button>
    </div>
</form>