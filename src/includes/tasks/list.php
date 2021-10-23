<?php
    if (!defined('SKDBM')) { die('Define missing'); }

    if ( isset( $_POST['new_entry'] ) )
    {
        $qry = "
            INSERT INTO
                `achievement_diaries`
                  (
                    `area`,
                    `difficulty`,
                    `task`,
                    `members`,
                    `quest_req`,
                    `quest_req_id`,
                    `skill_req`,
                    `strategy`,
                    `reward`
                  )
            VALUES (
                " . $db->quote( $_POST['area'] ) . ",
                " . $db->quote( $_POST['difficulty'] ) . ",
                " . $db->quote( $_POST['task'] ) . ",
                " . $db->quote( $_POST['members'] ) . ",
                " . $db->quote( $_POST['quest_names'] ) . ",
                " . $db->quote( $_POST['quest_ids'] ) . ",
                " . $db->quote( $_POST['skill_list'] ) . ",
                " . $db->quote( $_POST['strategy'] ) . ",
                " . $db->quote( $_POST['rewards'] ) . "
            )
        ";

        $db->query( $qry );

        logging( 'Tasks', '2', $_POST['task'], $db->lastInsertId() );

        echo '<div class="alert alert-success">Added the task, <strong>' . $_POST['task'] . '</strong> to the achievement diaries.</div>';
    }
    if ( isset( $_POST['edit_entry'] ) )
    {
        $qry = "
            UPDATE
                `achievement_diaries`
            SET
                `area` = " . $db->quote( $_POST['area'] ) . ",
                `difficulty` = " . $db->quote( $_POST['difficulty'] ) . ",
                `task` = " . $db->quote( $_POST['task'] ) . ",
                `members` = " . $db->quote( $_POST['members'] ) . ",
                `quest_req` = " . $db->quote( $_POST['quest_names'] ) . ",
                `quest_req_id` = " . $db->quote( $_POST['quest_ids'] ) . ",
                `skill_req` = " . $db->quote( $_POST['skill_list'] ) . ",
                `strategy` = " . $db->quote( $_POST['strategy'] ) . ",
                `reward` = " . $db->quote( $_POST['rewards'] ) . "
            WHERE
                `id` = " . $db->quote( $_POST['edit_entry'] ) . "
            LIMIT 1
        ";

        $db->query( $qry );

        logging( 'Tasks', '1', $_POST['task'], $_POST['edit_entry'] );

        echo '<div class="alert alert-success">The task, <strong>' . $_POST['task'] . '</strong> has been updated.</div>';
    }
    if ( isset( $_GET['del'] ) )
    {
        $logname = "SELECT `task` FROM `achievement_diaries` WHERE `id` = '{$_GET['del']}' LIMIT 1";
        $log = $db->query( $logname );
        $log = $log->fetch();

        $del_id = deleteLog( 'achievement_diaries', 'id', $_GET['del'] );

        $qry = "
            DELETE FROM
                `achievement_diaries`
            WHERE
                `id` = '{$_GET['del']}'
            LIMIT 1
        ";

        $db->query( $qry );

        $log_id = logging( 'Tasks', '0', $log['task'] );

        deleteLogReference( $del_id, $log_id );

        echo '<div class="alert alert-success">The task, <strong>' . $log['task'] . '</strong> has been deleted.</div>';
    }
?>
<div class="span12">
    <h4 class="pull-left">
        Achievement Diaries
    </h4>
    <div class="pull-right">
        <a href="index.php?s=tasks&p=new" class="btn btn-success" title="Add new entry">
            New entry
        </a>
    </div>
</div>

<?php
    $areas = $db->query( "SELECT DISTINCT `area` FROM `achievement_diaries` ORDER BY `area` ASC" );
    $areas = $areas->fetchAll();

    $area_clean    = array( '/', '\'', ' ' );
    $area_clean_to = array( '', '', '' );
?>

<ul class="nav nav-tabs">
    <?php
        $first = true;
        foreach ( $areas AS $area )
        {
            if ( $first ) {
                echo '<li class="active">';
                $first = false;
            }
            else
                echo '<li>';
            echo '
                    <a href="#' . trim( strtolower( str_replace( $area_clean, $area_clean_to, $area['area'] ) ) ) . '" data-toggle="tab">' . ucfirst( $area['area'] ) . '</a>
                </li>
            ';
        }
    ?>
</ul>

<div class="tab-content">
    <?php
        $first = true;
        foreach ( $areas AS $area )
        {
            if ( $first ) {
                echo '<div class="tab-pane fade active in" id="' . trim( strtolower( str_replace( $area_clean, $area_clean_to, $area['area'] ) ) ) . '">';
                $first = false;
            }
            else
                echo '<div class="tab-pane fade" id="' . trim( strtolower( str_replace( $area_clean, $area_clean_to, $area['area'] ) ) ) . '">';
    ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Area</th>
                    <th>Difficulty</th>
                    <th>Task</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ( $db->query( "SELECT * FROM `achievement_diaries` WHERE `area` = " . $db->quote( $area['area'] ) . " ORDER BY `difficulty` ASC, `task` ASC" ) AS $diary )
                {
                    $diff = "";
                    if ( $diary['difficulty'] == '0' )
                        $diff = 'Beginner';
                    if ( $diary['difficulty'] == '1' )
                        $diff = 'Easy';
                    if ( $diary['difficulty'] == '2' )
                        $diff = 'Medium';
                    if ( $diary['difficulty'] == '3' )
                        $diff = 'Hard';
                    if ( $diary['difficulty'] == '4' )
                        $diff = 'Elite';

                    echo '
                        <tr>
                            <td>
                                <a href="index.php?s=tasks&p=edit&id=' . $diary['id'] . '">
                                    ' . $diary['id'] . '
                                </a>
                            </td>
                            <td>
                                <a href="index.php?s=tasks&p=edit&id=' . $diary['id'] . '">
                                    ' . $diary['area'] . '
                                </a>
                            </td>
                            <td>' . $diff . '</td>
                            <td>' . $diary['task'] . '</td>
                            <td>
                                <a href="index.php?s=tasks&p=list&del=' . $diary['id'] . '" class="btn btn-danger do-confirm">
                                    Delete
                                </a>
                                <button type="button" class="task-copy btn btn-info" data-task-id="' . $diary['id'] . '">
                                    Copy task
                                </button>
                            </td>
                        </tr>
                    ';
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php
        }
    ?>
</div>