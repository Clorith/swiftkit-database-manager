<?php
/**
 * User: Marius
 * Date: 02.09.12
 * Time: 14:03
 */
?>

<h4 class="pull-left">
    Achievement Diaries
</h4>
<div class="pull-right">
    <a href="index.php?s=diaries&p=new" class="btn btn-success" title="Add new entry">
        New entry
    </a>
</div>

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
        foreach ( $db->query( "SELECT * FROM `achievement_diaries` ORDER BY `area` ASC, `difficulty` ASC, `task` ASC" ) AS $diary )
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
                        <a href="index.php?s=diaries&p=edit&id=' . $diary['id'] . '">
                            ' . $diary['id'] . '
                        </a>
                    </td>
                    <td>
                        <a href="index.php?s=diaries&p=edit&id=' . $diary['id'] . '">
                            ' . $diary['area'] . '
                        </a>
                    </td>
                    <td>' . $diff . '</td>
                    <td>' . $diary['task'] . '</td>
                    <td>
                        <a href="index.php?s=diaries&p=list&del=' . $diary['id'] . '" class="btn btn-danger do-confirm">
                            Delete
                        </a>
                    </td>
                </tr>
            ';
        }
    ?>
    </tbody>
</table>