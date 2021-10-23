<?php
/**
 * User: Marius
 * Date: 02.09.12
 * Time: 16:03
 */
?>
<form action="index.php?s=diaries&p=list" method="post" class="">
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
                    <option value="ag">Agility</option>
                    <option value="at">Attack</option>
                    <option value="aa">Combat</option>
                    <option value="hp">Constitution</option>
                    <option value="co">Construction</option>
                    <option value="ck">Cooking</option>
                    <option value="cr">Crafting</option>
                    <option value="de">Defence</option>
                    <option value="du">Dungeoneering</option>
                    <option value="fr">Farming</option>
                    <option value="fm">Firemaking</option>
                    <option value="fi">Fishing</option>
                    <option value="fl">Fletching</option>
                    <option value="hl">Herblore</option>
                    <option value="hu">Hunter</option>
                    <option value="ma">Magic</option>
                    <option value="mi">Mining</option>
                    <option value="pr">Prayer</option>
                    <option value="rn">Ranged</option>
                    <option value="rc">Runecrafting</option>
                    <option value="sl">Slayer</option>
                    <option value="sm">Smithing</option>
                    <option value="st">Strength</option>
                    <option value="su">Summoning</option>
                    <option value="th">Thieving</option>
                    <option value="wc">Woodcutting</option>
                </select>
                <button type="button" class="btn btn-info small" id="skill-add">Add</button>
            </div>
        </div>

        <div class="controls-row">
            <select name="quest_id_reqs" multiple="multiple" class="span4"></select>
            <select name="skill_tag_reqs" multiple="multiple" class="span4"></select>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add Quest</button>
    </div>
</form>