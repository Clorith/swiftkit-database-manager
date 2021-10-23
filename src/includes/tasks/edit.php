<?php
    if (!defined('SKDBM')) { die('Define missing'); }

    $data = $db->query( "SELECT * FROM `achievement_diaries` WHERE `id` = '{$_GET['id']}' LIMIT 1" );
    $data = $data->fetch();
    $data['quest_req_id'] = explode( '|', $data['quest_req_id'] );
?>
<form action="index.php?s=tasks&p=list" method="post" class="">
    <input type="hidden" name="edit_entry" value="<?php echo $_GET['id']; ?>" />

    <div class="control-group">
        <div class="controls-row">
            <label class="control-label span4" for="area">Area:</label>
            <label class="control-label span2" for="members">Members</label>
            <label class="control-label span2" for="difficulty">Difficulty</label>
        </div>
        <div class="controls">
            <div class="controls-row">
                <input class="span4" type="text" name="area" id="name" value="<?php echo $data['area']; ?>">
                <select class="span2" name="members" id="members">
                    <option<?php if ( $data['members'] == 'F2P' ) echo ' SELECTED'; ?>>F2P</option>
                    <option<?php if ( $data['members'] == 'P2P' ) echo ' SELECTED'; ?>>P2P</option>
                </select>
                <select class="span2" name="difficulty" id="difficulty">
                    <option value="0"<?php if ( $data['difficulty'] == '0' ) echo ' SELECTED'; ?>>Beginner</option>
                    <option value="1"<?php if ( $data['difficulty'] == '1' ) echo ' SELECTED'; ?>>Easy</option>
                    <option value="2"<?php if ( $data['difficulty'] == '2' ) echo ' SELECTED'; ?>>Medium</option>
                    <option value="3"<?php if ( $data['difficulty'] == '3' ) echo ' SELECTED'; ?>>Hard</option>
                    <option value="4"<?php if ( $data['difficulty'] == '4' ) echo ' SELECTED'; ?>>Elite</option>
                </select>
            </div>
        </div>
    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="control-label span4" for="task">Task</label>
        </div>
        <div class="controls-row">
            <input class="span8" type="text" name="task" id="task" value="<?php echo $data['task']; ?>">
        </div>
    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="control-label span4" for="rewards">Rewards</label>
            <label class="control-label span4" for="strategy">Strategy</label>
        </div>
        <div class="controls-row">
            <textarea class="span4" name="rewards" id="rewards"><?php echo $data['reward']; ?></textarea>
            <textarea class="span4" name="strategy" id="strategy"><?php echo $data['strategy']; ?></textarea>
        </div>
    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="control-label span4" for="quests">Required Quests</label>
            <label class="control-label span4" for="skill_level">Required Levels</label>
        </div>

        <div class="controls-row">
            <div class="input-append span4">
                <select id="quests">
                    <?php
                    $quests_done = "";
                    $quest_confirm = explode( ",", $data['quest_id_reqs'] );
                    foreach ($db->query( "SELECT * FROM `quests`" ) AS $row)
                    {
                        echo '<option value="' . $row['id'] .'">' . $row['name'] . '</option>';
                        if ( in_array( $row['id'], $quest_confirm ) )
                            $quests_done .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
                <button type="button" class="btn btn-info small" id="quest-add">Add</button>
            </div>

            <div class="input-append span4">
                <input type="number" step="any" id="skill_level">
                <select id="skill">
                    <?php
                        foreach ( $skill_list['diary'] AS $skill => $name )
                        {
							echo '<option value="' . $skill . '">' . $name . '</option>';
                        }
                    ?>
                </select>
                <button type="button" class="btn btn-info small" id="skill-add" data-add-type="task">Add</button>
            </div>
        </div>

        <div class="controls-row">
            <select name="quest_id_reqs" id="quest_id_reqs" multiple="multiple" class="span4"><?php echo $quests_done; ?></select>
            <select name="skill_tag_reqs" id="skill_tag_reqs" multiple="multiple" class="span4">
            <?php
                $skills = explode( "\n", $data['skill_req'] );
                for ( $i = 0; $i < count( $skills ); $i++ )
                {
                    $skill = explode( '=', str_replace( array( '<', '>' ), array( '', '' ), $skills[$i] ) );
                    echo '<option value="' . $skill[1] . '">' . $skill[1] . ' ' . $skill_list['diary'][$skill[0]] . '</option>';
                }
            ?>
            </select>
        </div>
    </div>

    <input type="hidden" name="quest_ids" id="quest_ids" value="<?php echo $data['quest_id_reqs']; ?>">
    <input type="hidden" name="quest_names" id="quest_names" value="<?php echo $data['quest_text_reqs']; ?>">
    <input type="hidden" name="skill_list" id="skill_list" value="<?php echo $data['skill_tag_reqs']; ?>">

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Edit Task</button>
    </div>
</form>