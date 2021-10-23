<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<?php

	if (isset($_GET['del']))
	{
        $logname = "SELECT `name` FROM `quests` WHERE `id` = '{$_GET['del']}' LIMIT 1";
		$qry = "DELETE FROM `quests` WHERE `id` = '{$_GET['del']}' LIMIT 1";

        $log = $db->query( $logname );
        $log = $log->fetch();

        $del_id = deleteLog( 'quests', 'id', $_GET['del'] );

		$db->query( $qry );

        $log_id = logging( 'Quests', '0', $log['name'] );

        deleteLogReference( $del_id, $log_id );

		echo '<div class="alert alert-success">The quest, <strong>' . $log['name'] . '</strong> has been deleted.</div>';
	}

	if (isset($_POST['edit_value']))
	{
        $qry = "
            UPDATE
                `quests`
            SET
                `name` = " . $db->quote( $_POST['name'] ) . ",
                `quest_points` = " . $db->quote( $_POST['quest_points'] ) . ",
                `members` = " . $db->quote( $_POST['members'] ) . ",
                `reward` = " . $db->quote( $_POST['reward'] ) . ",
                `qp_reqs` = " . $db->quote( $_POST['qp_reqs'] ) . ",
                `quest_id_reqs` = " . $db->quote( ( substr( $_POST['quest_ids'], -1 ) == ',' ? substr( $_POST['quest_ids'], 0, -1 ) : $_POST['quest_ids'] ) ) . ",
                `quest_text_reqs` = " . $db->quote( ( substr( $_POST['quest_names'], -1 ) == ',' ? substr( $_POST['quest_names'], 0, -1 ) : $_POST['quest_names'] ) ) . ",
                `skill_tag_reqs` = " . $db->quote( $_POST['skill_list'] ) . ",
                `skill_text_reqs` = " . $db->quote( ( substr( $_POST['skill_text'], -1 ) == ',' ? substr( $_POST['skill_text'], 0, -1 ) : $_POST['skill_text'] ) ) . ",
                `quest_coords` = " . $db->quote( $_POST['quest_coords'] ) . ",
                `advlog_questname` = " . $db->quote( $_POST['advlog_questname'] ) . "
            WHERE
                `id` = " . $db->quote( $_POST['edit_value'] ) . "
            LIMIT 1
        ";

		$db->query( $qry );

        logging( 'Quests', '1', $_POST['name'], $_POST['edit_value'] );

		echo '<div class="alert alert-success">You have updated the <strong>' . $_POST['name'] . '</strong> quest.</div>';
	}
	if (isset($_POST['new_entry']))
	{
		$qry = "
		    INSERT INTO
		        `quests`
		          (
		            `name`,
		            `quest_points`,
		            `members`,
		            `reward`,
		            `qp_reqs`,
		            `quest_id_reqs`,
		            `quest_text_reqs`,
		            `skill_text_reqs`,
		            `skill_tag_reqs`,
		            `quest_coords`,
		            `advlog_questname`
		          )
            VALUES (
                " . $db->quote( $_POST['name'] ) . ",
                " . $db->quote( $_POST['quest_points'] ) . ",
                " . $db->quote( $_POST['members'] ) . ",
                " . $db->quote( $_POST['reward'] ) . ",
                " . $db->quote( $_POST['qp_reqs'] ) . ",
                " . $db->quote( ( substr( $_POST['quest_ids'], -1 ) == ',' ? substr( $_POST['quest_ids'], 0, -1 ) : $_POST['quest_ids'] ) ) . ",
                " . $db->quote( ( substr( $_POST['quest_names'], -1 ) == ',' ? substr( $_POST['quest_names'], 0, -1 ) : $_POST['quest_names'] ) ) . ",
                " . $db->quote( ( substr( $_POST['skill_text'], -1 ) == ',' ? substr( $_POST['skill_text'], 0, -1 ) : $_POST['skill_text'] ) ) . ",
                " . $db->quote( $_POST['skill_list'] ) . ",
                " . $db->quote( $_POST['quest_coords'] ) . ",
                " . $db->quote( $_POST['advlog_questname'] ) . "
            )
		";

        $db->query( $qry );

        if ( $db->errorCode() <= 0 ) {
            logging( 'Quests', '2', $_POST['name'], $db->lastInsertId() );
            echo '<div class="alert alert-success">You have added the quest; <strong>' . $_POST['name'] . '</strong>.</div>';
        }
        else
            echo '<div class="alert alert-error">An error occurred and the quest, <strong>' . $_POST['name'] . '</strong> could not be added!</div>';
    }
?>
<div class="span12">
    <h4 class="pull-left">
        Quests
    </h4>
    <div class="pull-right">
        <a href="index.php?s=quests&p=new" class="btn btn-success" title="Add new entry">
            New entry
        </a>
    </div>
</div>

<ul class="nav nav-tabs" id="questTabs">
    <li class="active">
        <a href="#f2p" data-toggle="tab">Free Quests</a>
    </li>
    <li>
        <a href="#p2p" data-toggle="tab">Members Quests</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="f2p">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        Id
                    </th>
                    <th>
                        Quest Name
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ($db->query( "SELECT * FROM `quests` WHERE `members` = 'F2P' ORDER BY `name` ASC" ) AS $row)
                    {
                        echo '<tr>
                            <td>
                                <a href="index.php?s=quests&p=edit&id=' . $row['id'] .'">' . $row['id'] . '</a>
                            </td>
                            <td>
                                <a href="index.php?s=quests&p=edit&id=' . $row['id'] .'">' . $row['name'] . '</a>
                            </td>
                            <td>
                                <a href="index.php?s=quests&p=list&del=' . $row['id'] . '" class="btn btn-danger do-confirm">Delete</a>
                            </td>
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>

    <div class="tab-pane" id="p2p">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        Id
                    </th>
                    <th>
                        Quest Name
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>
	
            <tbody>
                <?php
                    foreach ($db->query( "SELECT * FROM `quests` WHERE `members` = 'P2P' ORDER BY `name` ASC" ) AS $row)
                    {
                        echo '<tr>
                            <td>
                                <a href="index.php?s=quests&p=edit&id=' . $row['id'] .'">' . $row['id'] . '</a>
                            </td>
                            <td>
                                <a href="index.php?s=quests&p=edit&id=' . $row['id'] .'">' . $row['name'] . '</a>
                            </td>
                            <td>
                                <a href="index.php?s=quests&p=list&del=' . $row['id'] . '" class="btn btn-danger do-confirm">Delete</a>
                            </td>
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>