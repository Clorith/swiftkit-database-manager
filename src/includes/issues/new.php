<?php
/**
 * User: Marius Jensen
 * Date: 05.10.12
 * Time: 15:26
 */
?>

<form action="" method="post">
    <div class="control-group">
        <label class="control-label" for="summary">Summary</label>
        <div class="controls">
            <input type="text" name="summary" id="summary">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="description">Description</label>
        <div class="controls">
            <textarea name="description" id="description"></textarea>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span6">
            <div class="control-group">
                <label class="control-label" for="severity">Severity</label>
                <div class="controls">
                    <select name="severity" id="severity">
                        <option value="5">Critical</option>
                        <option value="4">Major</option>
                        <option value="3" SELECTED>Normal</option>
                        <option value="2">Minor</option>
                        <option value="1">Trivial</option>
                    </select>
                </div>
            </div>

        </div>

        <div class="span6">
            <div class="control-group">
                <label class="control-label" for="component">Component</label>
                <div class="controls">
                    <input type="text" name="component" id="component">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="version">Version</label>
                <div class="controls">
                    <input type="text" name="version" id="version">
                </div>
            </div>

        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Add issue</button>
    </div>
</form>