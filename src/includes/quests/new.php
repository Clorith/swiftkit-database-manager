<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<h4>
	Quests
</h4>
<form action="index.php?s=quests&p=list" method="post">
	<input type="hidden" name="new_entry" value="true" />

    <div class="control-group">
        <div class="controls-row">
            <label class="span4 control-label" for="name">Quest Name</label>
            <label class="span2 control-label" for="quest_points">Quest Points</label>
        </div>
        <div class="controls-row">
            <input type="text" name="name" id="name" class="span4">
            <input type="number" name="quest_points" id="quest_points" step="any" class="span2">
        </div>
    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="span4 control-label" for="quest_coords">Location (From SwiftKit Atlas)</label>
            <label class="span2 control-label" for="qp_reqs">Required Quest Points</label>
        </div>
        <div class="controls-row">
            <div class="input-append span4">
                <input type="text" name="quest_coords" id="quest_coords" class="span11 map-coords">
                <button class="btn open-map-position" type="button" href="#map-position" data-toggle="modal"><i class="icon-globe"></i></button>
            </div>
            <input type="number" min="0" name="qp_reqs" id="qp_reqs" class="span2">
        </div>
    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="span4 control-label" for="reward">Quest rewards</label>
            <label class="span2 control-label" for="members">Members</label>
        </div>
        <div class="controls-row">
            <textarea name="reward" id="reward" class="span4"></textarea>
            <select name="members" id="members" class="span2">
                <option value="F2P">F2P</option>
                <option value="P2P">P2P</option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="offset4 span2 control-label" for="advlog-fetch">Adventure Log Name</label>
        </div>
        <div class="controls-row">
            <div class="offset4 input-append span4">
                <select name="advlog_questname" id="advlog">
                    <?php
                        echo advlog();
                    ?>
                </select>
                <button type="button" class="btn btn-info" id="advlog-fetch"><i class="icon-refresh icon-white"></i></button>
            </div>
        </div>

    </div>

    <div class="control-group">
        <div class="controls-row">
            <label class="span4 control-label" for="quests">Required Quests</label>
            <lanel class="span4 control-label" for="skill">Required Levels</lanel>
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
                <button type="button" class="btn btn-info" id="quest-add">Add</button>
            </div>
            <div class="input-append span4">
                <input type="number" step="any" id="skill_level" class="col_2">
                <select id="skill">
                    <?php
                        foreach ( $skill_list['quest'] AS $short => $full )
                        {
                            echo '<option value="' . $short . '">' . $full . '</option>';
                        }
                    ?>
                </select>
                <button type="button" class="btn btn-info" id="skill-add">Add</button>
            </div>
        </div>

        <div class="controls-row">
            <select id="quest_id_reqs" name="quest_id_reqs" multiple="multiple" class="span4"></select>
            <input type="hidden" name="quest_ids" id="quest_ids" value="">
            <input type="hidden" name="quest_names" id="quest_names" value="">

            <select id="skill_tag_reqs" name="skill_tag_reqs" multiple="multiple" class="span4"></select>
            <input type="hidden" name="skill_list" id="skill_list" value="">
            <input type="hidden" name="skill_text" id="skill_text" value="">
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add Quest</button>
    </div>
</form>

<div class="modal container hide fade" id="map-position">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Location</h3>
    </div>

    <div class="modal-body">
        <input type="text" name="location_name" id="location_name" placeholder="Location Name">
        <div id="map">
            Loading RuneScape World Map
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="map_location" data-dismiss="modal">Set location</button>
    </div>
</div>