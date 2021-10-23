<?php
    if (!defined('SKDBM')) { die('Define missing'); }
?>
<form action="index.php?s=tasks&p=list" method="post" class="">
    <input type="hidden" name="new_entry" value="true" />

    <div class="control-group">
        <div class="controls-row">
            <label class="control-label span4" for="area">Area:</label>
            <label class="control-label span2" for="members">Members</label>
            <label class="control-label span2" for="difficulty">Difficulty</label>
        </div>
        <div class="controls">
            <div class="controls-row">
                <input class="span4" type="text" name="area" id="area">
                <select class="span2" name="members" id="members">
                    <option>F2P</option>
                    <option>P2P</option>
                </select>
                <select class="span2" name="difficulty" id="difficulty">
                    <option value="0">Beginner</option>
                    <option value="1">Easy</option>
                    <option value="2">Medium</option>
                    <option value="3">Hard</option>
                    <option value="4">Elite</option>
                </select>
            </div>
        </div>
    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="control-label span4" for="task">Task</label>
        </div>
        <div class="controls-row">
            <input class="span8" type="text" name="task" id="task">
        </div>
    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="control-label span4" for="rewards">Rewards</label>
            <label class="control-label span4" for="strategy">Strategy</label>
        </div>
        <div class="controls-row">
            <textarea class="span4" name="rewards" id="rewards"></textarea>
            <textarea class="span4" name="strategy" id="strategy"></textarea>
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
                    foreach ($db->query( "SELECT * FROM `quests`" ) AS $row)
                    {
                        echo '<option value="' . $row['id'] .'">' . $row['name'] . '</option>';
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
            <select name="quest_id_reqs" id="quest_id_reqs" multiple="multiple" class="span4"></select>
            <select name="skill_tag_reqs" id="skill_tag_reqs" multiple="multiple" class="span4"></select>
        </div>
    </div>

    <input type="hidden" name="quest_names" id="quest_names" />
    <input type="hidden" name="quest_ids" id="quest_ids" />
    <input type="hidden" name="skill_list" id="skill_list" />

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add Task</button>
        <?php
            if ( isset( $_COOKIE['SKDBM-Data-Task'] ) && ! empty( $_COOKIE['SKDBM-Data-Task'] ) )
                echo '<buttontype="button" class="task-autofill btn btn-info pull-right">Autofill</button>';
        ?>
    </div>
</form>

<?php
    if ( isset( $_COOKIE['SKDBM-Data-Task'] ) && ! empty( $_COOKIE['SKDBM-Data-Task'] ) ) {

    $aTask = unserialize( $_COOKIE['SKDBM-Data-Task'] );
?>
<input type="hidden" id="autofill-area" value="<?php echo $aTask['area']; ?>" />
<input type="hidden" id="autofill-difficulty" value="<?php echo $aTask['difficulty']; ?>" />
<input type="hidden" id="autofill-members" value="<?php echo $aTask['members']; ?>" />
<input type="hidden" id="autofill-rewards" value="<?php echo $aTask['reward']; ?>" />
<?php } ?>
