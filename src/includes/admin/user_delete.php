<?php
if (!defined('SKDBM')) { die('Define missing'); }

$data = $db->query( "SELECT `username` FROM `users` WHERE `uid` = '{$_GET['id']}' LIMIT 1" );

?>
<h4>
    Delete system user
</h4>
<form action="index.php?s=admin&p=users" method="post">
    <input type="hidden" name="delete_user" value="<?php echo $_GET['id']; ?>" />
    <strong>
        You are about to delete this user, are you sure this is what you want to do?
    </strong>
    <br><br>
    <input type="submit" class="red" value="Delete the user" />
</form>