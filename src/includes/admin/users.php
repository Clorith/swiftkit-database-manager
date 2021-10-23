<?php
    if (!defined('SKDBM')) { die('Define missing'); }
    if (ua() < 100) { die('Insufficient access'); }

    if ( isset( $_POST['new_user'] ) )
    {
        $db->query( "
            INSERT INTO
                `users`
                  (`username`,`password`,`access`)
            VALUES (
                '{$_POST['new_user']}',
                '" . sha1( $_POST['new_pass'] ) . "',
                '{$_POST['new_access']}'
            )
        " );
    }

    if ( isset( $_POST['edit_user'] ) )
    {
        $db->query( "
            UDPATE
                `users`
            SET
                `username` = '{$_POST['edit_user']}',
                `password` = '" . sha1( $_POST['edit_pass'] ) . "',
                `access` = '{$_POST['edit_access']}'
            WHERE
                `uid` = '{$_POST['edit_id']}'
            LIMIT 1
        " );
    }

    if ( isset( $_GET['delete_id'] ) )
    {
        $db->query( "
            DELETE FROM
                `users`
            WHERE
                `uid` = '{$_GET['delete_id']}'
            LIMIT 1
        " );
    }

    if ( isset( $_GET['clear_id'] ) )
    {
        $db->query( "
            UPDATE
                `users`
            SET
                `auth` = '',
                `ip_lock` = ''
            WHERE
                `uid` = '{$_GET['clear_id']}'
            LIMIT 1
        " );
    }
?>

<h4 class="pull-left">
    User management
</h4>
<div class="pull-right">
    <a href="#new" class="btn btn-success" title="Add new entry" data-toggle="modal">
        New user
    </a>
</div>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Username</th>
        <th>Last login time</th>
        <th>Last login from</th>
        <th>Actions</th>
    </tr>
    </thead>

    <tbody>
    <?php

    foreach ($db->query( "SELECT `username`, `uid`, `lastip`, `login_time` FROM `users`" ) AS $row)
    {
        echo '
            <tr name="' . $row['uid'] . '">
                <td>' . $row['username'] . '</td>
                <td>' . ( $row['login_time'] > 0 ? date( "d.m.Y H:i", $row['login_time'] ) : 'No login logged' ) . '</td>
                <td>' . $row['lastip'] . '</td>
                <td>
                    <a href="#edit" class="btn btn-info user-edit" data-toggle="modal">Edit user</a>
                    <a href="index.php?s=admin&p=users&delete_id=' . $row['uid'] . '" class="btn btn-danger do-confirm">Delete user</a>
                    <a href="index.php?s=admin&p=users&clear_id=' . $row['uid'] . '" class="btn btn-warning do-confirm">Clear G-Auth / IP-Lock</a>
                </td>
            </tr>
        ';
    }
    ?>
    </tbody>
</table>

<div id="new" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal">x</button>
        <h3>New user</h3>
    </div>

    <div class="modal-body">
        <form action="index.php?s=admin&p=users" method="post" class="form-horizontal" id="add-user">
            <div class="control-group">
                <label class="control-label" for="new_user">Username</label>
                <div class="controls">
                    <input type="text" name="new_user" id="new_user">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="new_pass">Password</label>
                <div class="controls">
                    <input type="password" name="new_pass" id="new_pass">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="new_access">Access</label>
                <div class="controls">
                    <select name="new_access" id="new_access">
                        <option value="0">Disabled</option>
                        <option value="1">Regular user</option>
                        <option value="50">Developer</option>
                        <option value="100">Administrator</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary form-submit" name="#add-user">Add user</button>
    </div>
</div>

<div id="edit" class="modal hide fade" tabindex="-1" rolde="dialog" aria-hidden="true">
    <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal">x</button>
        <h3>Edit user</h3>
    </div>

    <div class="modal-body">
        <form action="index.php?s=admin&p=users" method="post" class="form-horizontal" id="edit-user">
            <input type="hidden" name="edit_id" id="edit_id" value="">
            <div class="control-group">
                <label class="control-label" for="edit_user">Username</label>
                <div class="controls">
                    <input type="text" name="edit_user" id="edit_user" value="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="edit_pass">Password</label>
                <div class="controls">
                    <input type="password" name="edit_pass" id="edit_pass">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="edit_access">Access</label>
                <div class="controls">
                    <select name="edit_access" id="edit_access">
                        <option value="0">Disabled</option>
                        <option value="1">Regular user</option>
                        <option value="50">Developer</option>
                        <option value="100">Administrator</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary form-submit" name="#edit-user">Edit user</button>
    </div>
</div>