jQuery(document).ready(function($) {
    //  Global variables definition area
    current_url = window.location.pathname + window.location.search;

    //  Update the adventure log and add the content to our dropdown
    $("#advlog-fetch").click(function() {
        var loadIcon = $(this).html();
        $(this).html( '<img src="images/ajax-loader.gif" alt="loading..." />' );
        $.ajax({
            type: 'POST',
            data: 'sel=' + $("#advlog").find('option:selected').text(),
            url:    'ajax/quests.php',
            success: function(data) {
                $("#advlog").html(data);
                $("#advlog-fetch").html( loadIcon );
            }
        });
        return false;
    });

    //  Require confirmation befoer completing an action
    $(".do-confirm").live( 'click', function(e) {
        if ( confirm( 'Are you sure you wish to perform this action?' ) ) {
            return true;
        }
        else {
            e.preventDefault();
            return false;
        }
    });

    //  Inline editing for skill lists
    $(".skill-edit span").live( 'click', function(e) {
        var makehtml = '<input type="text" value="' + $(this).text() + '" column="' + $(this).closest('td').attr('column') + '">';

        $(this).html(makehtml);
        $('input', $(this)).focus();
    });
    $(".skill-edit input").live( 'blur', function(e) {
        var column = encodeURIComponent($(this).attr('column'));
        var value = encodeURIComponent($(this).attr('value'));
        var parent = encodeURIComponent($(this).closest('tr').attr('parent'));
        var parent_val = encodeURIComponent($(this).closest('tr').attr('parent_val'));
        $.ajax({
            type: 'POST',
            url: 'ajax/skill.php',
            data: 'skill=' +  encodeURIComponent($("#skill_name").text()) + '&column=' + column + '&value=' + value + '&parent=' + parent + '&parent_val=' + parent_val
        });
        var makehtml = '<span>' + $(this).val() + '</span>';
        $(this).closest('td').html(makehtml);
    }).live( 'keypress', function(e) {
        if (e.which == 13) {
            var column = encodeURIComponent($(this).attr('column'));
            var value = encodeURIComponent($(this).attr('value'));
            var parent = encodeURIComponent($(this).closest('tr').attr('parent'));
            var parent_val = encodeURIComponent($(this).closest('tr').attr('parent_val'));
            $.ajax({
                type: 'POST',
                url: 'ajax/skill.php',
                data: 'skill=' + encodeURIComponent($("#skill_name").text()) + '&column=' + column + '&value=' + value + '&parent=' + parent + '&parent_val=' + parent_val
            });
            var makehtml = '<span>' + $(this).val() + '</span>';
            $(this).closest('td').html(makehtml);
        }
    });

    //  Inline editing for the atlas pages
    $(".atlas-edit span").live( 'click', function(e) {
        var makehtml = '<input type="text" value="' + $(this).text() + '" column="' + $(this).closest('td').attr('column') + '">';

        $(this).html(makehtml);
        $('input', $(this)).focus();
    });
    $(".atlas-edit input").live( 'blur', function(e) {
        var column = encodeURIComponent($(this).attr('column'));
        var value = encodeURIComponent($(this).attr('value'));
        var parent = encodeURIComponent($(this).closest('tr').attr('parent'));
        var parent_val = encodeURIComponent($(this).closest('tr').attr('parent_val'));
        $.ajax({
            type: 'POST',
            url: 'ajax/atlas.php',
            data: 'atlas=' +  encodeURIComponent($("#atlas_name").text()) + '&column=' + column + '&value=' + value + '&parent=' + parent + '&parent_val=' + parent_val
        });
        var makehtml = '<span>' + $(this).val() + '</span>';
        $(this).closest('td').html(makehtml);
    }).live( 'keypress', function(e) {
        if (e.which == 13) {
            var column = encodeURIComponent($(this).attr('column'));
            var value = encodeURIComponent($(this).attr('value'));
            var parent = encodeURIComponent($(this).closest('tr').attr('parent'));
            var parent_val = encodeURIComponent($(this).closest('tr').attr('parent_val'));
            $.ajax({
                type: 'POST',
                url: 'ajax/atlas.php',
                data: 'atlas=' + encodeURIComponent($("#atlas_name").text()) + '&column=' + column + '&value=' + value + '&parent=' + parent + '&parent_val=' + parent_val
            });
            var makehtml = '<span>' + $(this).val() + '</span>';
            $(this).closest('td').html(makehtml);
        }
    });

    //  Update multi lined selects in the Quest modification lists
    function quest_update_skill_list( usefor ) {
		usefor = typeof usefor !== 'undefined' ? usefor : 'quest';

        var skill_list = "";
        var skill_text = "";

		if ( usefor == 'task' ) {
			$("#skill_tag_reqs option").each(function(e) {
				skill_list = skill_list + "<" + $(this).attr('name') + "=" + $(this).val() + ">";
				skill_text = skill_text + $(this).text() + ",";
			});
		} else {
			$("#skill_tag_reqs option").each(function(e) {
				skill_list = skill_list + "<" + $(this).attr('name') + ">" + $(this).val() + "</" + $(this).attr('name') + ">";
				skill_text = skill_text + $(this).text() + ",";
			});
		}
        $("#skill_list").val(skill_list);
        $("#skill_text").val(skill_text);
        return true;
    }
    function quest_update_quest_list() {
        var quest_id = "";
        var quest_name = "";
        $("#quest_id_reqs option").each(function(e) {
            quest_id = quest_id + $(this).val() + ',';
            quest_name = quest_name + $(this).text() + ',';
        });
        $("#quest_ids").val(quest_id);
        $("#quest_names").val(quest_name);
    }
    $("#skill-add").click(function(e) {
        var $skill = $("#skill").children("option").filter(":selected");
        $("<option value=\"" + $("#skill_level").val() + "\" name=\"" + $skill.val() + "\">" + $("#skill_level").val() + " " + $skill.text() + "</option>").appendTo("#skill_tag_reqs");

		var add_type = $(this).data('add-type');

		if ( add_type != 'undefined' ) {
			quest_update_skill_list( add_type );
		} else {
			quest_update_skill_list();
		}
    });
    $("#quest-add").click(function(e) {
        var $quest = $("#quests").children("option").filter(":selected");
        $('<option value="' + $quest.val() + '">' + $quest.text() + '</option>').appendTo("#quest_id_reqs");
        quest_update_quest_list();
    });

    //  Delete button used in a Quest modification list
    $("#skill_tag_reqs").keyup(function(e) {
        if (e.which == 46) {
            $(this).children('option').filter(':selected').remove();
            quest_update_skill_list();
        }
    });
    $("#quest_id_reqs").keyup(function(e) {
        if (e.which == 46) {
            $(this).children('option').filter(':selected').remove();
            quest_update_quest_list();
        }
    });

    //  Skill editing, modal view
    $(".skill-edit").live( 'click', function(e) {
        var $parent = $(this).closest('tr');
        $("#edit_key").val($parent.attr('parent'));
        $("#edit_value").val($parent.attr('parent_val'));

        $.ajax({
            type: 'POST',
            data: 'skill=' + encodeURIComponent($("#skill_name").text()) + '&edit_key=' + encodeURIComponent($("#edit_key").val()) + '&edit_value=' + encodeURIComponent($("#edit_value").val()),
            dataType: 'json',
            url: 'ajax/skill_data.php',
            success: function(data) {
                $('td', $parent).each(function() {
                    //$("#e_" + $(this).attr('column')).val($(this).attr('value'));
                    if (data[$(this).attr('column')] !== undefined) {
                        $("#e_" + $(this).attr('column')).val(data[$(this).attr('column')]);
                    }
                })
            }
        })

        e.preventDefault();
    });

    //  Alert remove on timer
    if ($(".alert").length >= 1) {
        window.setTimeout(function() {
            $(".alert").fadeOut();
        }, 3000);
    }

    //  Add new to listing
    $("#add_skill").click(function(e) {
        $("#add_skill_form").submit();
    });
    $("#add_fairyring").click(function(e) {
        $("#newEntry").submit();
    });

    //  Modify fairy ring locations
    $(".update_fairyring").click(function(e) {
        $.ajax({
            type: 'POST',
            data: 'code=' + encodeURIComponent($(this).attr('name')),
            dataType: 'json',
            url: 'ajax/fairyring_data.php',
            success: function(data) {
                $("#edit_value").val(data.code);
                $("#edit-code").val(data.code);
                $("#edit-location").val(data.location);
                $("#edit-coords").val(data.coords);
            }
        });
        e.preventDefault();
    });

    //  Modal edit window for users
    $(".user-edit").click(function (e) {
        $.ajax({
            type: 'POST',
            data: 'user=' + encodeURIComponent($(this).closest('tr').attr('name')),
            dataType: 'json',
            url: 'ajax/user_details.php',
            success: function(data) {
                $("#edit_user").val(data.username);
                $("edit_pass").val('');
                $("#edit_access").val(data.access);
                $("#edit_id").val(data.uid);
            }
        });
    });

    function saveFairyring() {
        var $tr = $('tr[parent_val="' + $("#edit_value").val() + '"]');
        var newLine = '<td><a href="#edit" data-toggle="modal" class="update_fairyring" name="' + $("#edit-code").val() + '">' + $("#edit-code").val() + '</a></td><td>' + $("#edit-location").val() + '</td><td><a href="index.php?s=fairyrings&p=list&del=' + $("#edit-code").val() + '" class="btn btn-danger do-confirm">Delete</a></td>';
        var data = 'code=' + encodeURIComponent($("#edit-code").val()) + '&location=' + encodeURIComponent($("#edit-location").val()) + '&coords=' + encodeURIComponent($("#edit-coords").val());

        $.ajax({
            type: 'POST',
            data: data,
            url: 'ajax/fairyring_update.php?code=' + $("#edit_value").val(),
            success: function(data) {
                $tr.html( data );
                $tr.attr('parent_val', $("#edit-code"));
                $tr.html( newLine );
            }
        })
    }
    function saveSkill() {
        var $tr = $('tr[parent_val="' + $("#edit_value").val() + '"]');
        var data = "edit_key=" + encodeURIComponent($("#edit_key").val()) + "&edit_value=" + encodeURIComponent($("#edit_value").val());

        $('.controls', $("#edit")).each(function() {
            var $td = $(this).find('input, textarea');
            if ($td.length >= 1 && $td.attr('name') !== undefined) {
                data = data + "&" + encodeURIComponent($td.attr('name')) + "=" + encodeURIComponent($td.val());
                var $trtd = $('td[column="' + $td.attr('name') + '"]', $tr);
                $trtd.attr('value', $td.val());
                $trtd.html('<span>' + $td.val() + '</span>');
            }
        });
        var $link = $("td", $tr).first();
        $link.html('<a href="#edit" class="edit-skill" data-toggle="modal" rel="edit">' + $link.text() + '</a>');
        $.ajax({
            type: 'POST',
            data: data,
            url: 'ajax/skill_update.php?skill=' + $("#skill_name").text(),
            success: function(data) {
                $('tr[parent_val="' + $("#edit_value").val() + '"]').attr('parent_val', $("#e_" + $('tr[parent_val="' + $("#edit_value").val() + '"]').attr('parent')).val());
            }
        })
    }

    $("#skill_update_button").click(function(e) {
        saveSkill();
    });
    $("#skill_update").submit(function(e) {
        saveSkill();
        e.preventDefault();
    });
    $("#edit_fairyring").click(function(e) {
        saveFairyring();
    });
    $(".skill-header").click(function(e) {
        $(".skill").toggle();
        if ($(".skill").is(":visible")) {
            $(this).removeClass("nav-parent-expand").addClass("nav-parent-collapse");
        }
        else {
            $(this).removeClass("nav-parent-collapse").addClass("nav-parent-expand");
        }
    });

    $(".form-submit").click(function (e) {
        $($(this).attr('name')).submit();
    });

    //  Fix for google maps struggling if the map is loaded before the modals are positioned (gives funky positioning)
    $(".open-map-position").click(function (e) {
        setTimeout(load, 1000);
    });
    $("#map_location").click(function (e) {
        $.ajax({
            type: 'POST',
            data: 'ns=' + sendposY + '&ew=' + sendposX,
            url: 'ajax/location.php',
            success: function (data) {
                if ($(".map-coords").length >= 1) {
                    $(".map-coords").val($("#location_name").val() + ',' + data);
                }
                if ($(".location").length >= 1 ) {
                    $(".location").val(data);
                }
            }
        });
    });

    //  Neverending log scroll, only active on the log page
    if ($(".logList").length >= 1) {
        $("#logList").infinitescroll({
            navSelector: 'div.pagination',
            nextSelector: 'a.nextPage',
            itemSelector: '.logListEntry',
            debug: true
        });
    }

    //  Find hash tags for opening modal windows from logview
    if (window.location.hash) {
        var id = window.location.hash.replace('#id=', '');
        var $parent = $('[parent_val="' + id + '"]');
        $("#edit_key").val($parent.attr('parent'));
        $("#edit_value").val($parent.attr('parent_val'));

        $.ajax({
            type: 'POST',
            data: 'skill=' + encodeURIComponent($("#skill_name").text()) + '&edit_key=' + encodeURIComponent($("#edit_key").val()) + '&edit_value=' + encodeURIComponent($("#edit_value").val()),
            dataType: 'json',
            url: 'ajax/skill_data.php',
            success: function(data) {
                $("#edit").modal('show');
                $('td', $parent).each(function() {
                    //$("#e_" + $(this).attr('column')).val($(this).attr('value'));
                    if (data[$(this).attr('column')] !== undefined) {
                        $("#e_" + $(this).attr('column')).val(data[$(this).attr('column')]);
                    }
                })
            },
            error: function(data) {
                console.dir(data);
            }
        });

    }

    //  Copy task data to cookie for further use
    $(".task-copy").click(function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: 'id=' + $(this).attr('data-task-id'),
            url: 'ajax/task_copy.php',
            success: function (data) {
                console.dir(data);
                if (data != '') {
                    $("#notifications").append('<div class="alert alert-success"><h4>Task copied</h4>The task data has been copied and can now be auto-implemented into new tasks.</div>');
                }
            }
        });
    });
    //  Autofill new task data
    $(".task-autofill").click(function (e) {
        e.preventDefault();
        $("#area").val($("#autofill-area").val());
        $("#members").val($("#autofill-members").val());
        $("#difficulty").val($("#autofill-difficulty").val());
        $("#rewards").val($("#autofill-rewards").val());
    });
});