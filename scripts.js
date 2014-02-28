// JavaScript Document

// Create a ace editor
window.createAce = function (editorid, areaid, mode, theme, flag, comment) {

	ace.require("ace/ext/language_tools");

    // Create ace
    var editor = ace.edit(editorid);

    // Set the ace theme
    editor.setTheme("ace/theme/" + theme);
	
	editor.setOptions({
		enableBasicAutocompletion : true
	});

    // Set the ace mode
    if (mode != null && mode != '')
        editor.getSession().setMode("ace/mode/" + mode);

    /*switch to call different Ace editor Mode
     "configure" : used for language configuration editor, provides code, import and test tags
     "code" : used for problem code configuration editor, provides code tag
     "unit-test" : used for choosing the unit test to display, provides unit-test tag
     "resolution" : used to solve problem. allows user to type only betweens <code></code> tags
     "readonly" : used to display problem's code. not editable
     */
    switch (flag) {
        case "configure":
            createConfigure(editorid);
            break;
        case "code":
            createCode(editorid, comment, areaid);
            break;
        case "unit-test":
            createUnitTest(editorid, areaid);
            break;
        case "resolution":
            createResolution(editorid, areaid);
            break;
        case "readonly":
            createReadOnly(editorid, true);
            break;
        case "readonly-notsizable":
            createReadOnly(editorid, false);
            break;
        default:
            editor.getSession().setValue($("#" + areaid).val());
            break;
    }

    // Copy the ace content on the area
    if (areaid != null && areaid != '') {
        editor.getSession().on("change", function () {
            $("#" + areaid).val(editor.getSession().getValue());
        });
    }
}


//Create an ace editor in resolution mode
//used to solve problem. allows user to type only betweens <code></code> tags
window.createResolution = function (editorid, areaid) {
    // Create ace
    var editor = ace.edit(editorid);

    // Copy the area content on ace
    editor.getSession().setValue($("#" + areaid).val());
}


//Create an ace editor in Language configuration mode
//used for language configuration editor, provides code, import and test tags
window.createConfigure = function (editorid) {

    // Create ace
    var editor = ace.edit(editorid);

    // Tag editor
    $("#" + editorid).before('<div class="acepanel">' +
        '<a href="#" id="' + editorid + '_tagImport">Import</a>' +
        '<a href="#" id="' + editorid + '_tagCode">Code</a>' +
        '<a href="#" id="' + editorid + '_tagTests">Tests</a>' +
        '</div>');

    // Click on the import tag
    $("#" + editorid + "_tagImport").click(function () {
        if (editor.findAll('Import', null, true) == 0)
            editor.insert("<lips-preconfig-import/>");
        editor.focus();

        return false;
    });

    // Click on the code tag
    $("#" + editorid + "_tagCode").click(function () {
        if (editor.findAll('Code', null, true) == 0)
            editor.insert("<lips-preconfig-code/>");
        editor.focus();

        return false;
    });

    // Click on the tests tag
    $("#" + editorid + "_tagTests").click(function () {
        if (editor.findAll('Tests', null, true) == 0)
            editor.insert("<lips-preconfig-tests/>");
        editor.focus();

        return false;
    });
}


//Create an ace editor in code mode
//used for problem code configuration editor, provides code tag
window.createCode = function (editorid, comment, areaid) {

    // Create ace
	var editor = ace.edit(editorid);

	if (comment != ""){
		$("#" + editorid).before('<div class="acepanel">' +
		'<a href="#" id="' + editorid + '_tagCode">Comment</a>' + 
		'</div>');

        editor.getSession().setValue($("#" + areaid).val());

        // Click on the code tag
		$("#" + editorid + "_tagCode").click(function(){
			editor.insert(comment);
			editor.focus();
			return false;
		});	
	}
}

//Create an ace editor in unit-test mode
//used for choosing the unit test to display, provides unit-test tag
window.createUnitTest = function (editorid, areaid) {

    // Create ace
    var editor = ace.edit(editorid);

    $("#" + editorid).before('<div class="acepanel">' +
        '<a href="#" id="' + editorid + '_tagUnit">Unit Test</a>' +
		'<a href="#" id="' + editorid + '_tagSolved">True</a>' +
		'<a href="#" id="' + editorid + '_tagFailed">False</a>' +
        '</div>');

    editor.getSession().setValue($("#" + areaid).val());

    // Click on the code tag
    $("#" + editorid + "_tagUnit").click(function () {

        if (editor.getCopyText().length == 0) {
            editor.insert("<lips-unit-test></lips-unit-test>");
			editor.focus();
        } else {
            var currentText = editor.getCopyText();
            editor.remove(editor.getSelectionRange());
            editor.insert("<lips-unit-test>" + currentText + "</lips-unit-test>");
			editor.focus();
        }
		
        return false;
    });
	
	  // Click on the code tag
    $("#" + editorid + "_tagSolved").click(function () {
		editor.insert("PROBLEM_SOLVED");
		editor.focus();
		
		return false;
	});
	
	 $("#" + editorid + "_tagFailed").click(function () {
		editor.insert("PROBLEM_FAILED");
		editor.focus();
		
		return false;
	});
}

//Create an ace editor in readonly mode
//used to display problem's code. not editable
window.createReadOnly = function (editorid, sizable) {
    var editor = ace.edit(editorid);
    var doc = editor.getSession().getDocument();
    var lines = doc.getLength();
    var allLines = doc.getAllLines();
    var longestLine = 0;
    var longestIndex = 0;
    editor.setReadOnly(true);
    editor.renderer.setShowGutter(false);
    editor.setHighlightActiveLine(false);

    if(sizable) {
        for (var i in allLines) {
            if (longestLine < allLines[i].length) {
                longestLine = allLines[i].length;
                longestIndex = i;
            }
        }

        for (var j in allLines[longestIndex]) {
            if (allLines[longestIndex][j] == "\t")
                longestLine = longestLine + 3;
        }

        $("#" + editorid).css("width", (20 + (longestLine * 7)));
    }

    $("#" + editorid).css("height", lines * 16);
}

// Delete the challenged user
function deleteChalengedUser(user_id) {
    $("#" + user_id).remove();
}

function deleteSimilarProblem(id) {
    $("#similar-problems #" + id).remove();
    $("#id_problem_similar").val($("#id_problem_similar").val().replace(" " + id, ""));
}

function populateselectinstance() {
    var idinstance = $("#id_language_id_js option:selected").val();
    $.getJSON("./get_categories_by_instance.php", {id: idinstance, ajax: 'true'}, function (j) {
        var options = "<option value='all'>Tout</option>";
        $.each(j, function (key, val) {
            options += '<option value="' + val.id_language + '">' + val.category_name + '</option>';
        });
        $("#id_category_id_js").html(options);
    })
}

$(document).ready(function () {

    /*---------------------------------
     *  Ajax to populate select of similar problem
     *-------------------------------*/

    if ($('#dialog').length > 0) {
        $('#dialog').dialog({
            height: 250,
            width: 400,
            modal: true,
            autoOpen: false,
            draggable: false,
            buttons: {
                Conseiller: function () {
                    if ($.inArray($("#id_problem_id_js option:selected").val(), $("#id_problem_similar").val().split(" ")) == -1) {
                        $("#similar-problems #problems").append('<p id="' + $("#id_problem_id_js option:selected").val() + '">' + $("#id_problem_id_js option:selected").text() + '<img src="images/delete_similar.png" title="Supprimer" class="delete" onClick="deleteSimilarProblem(' + $("#id_problem_id_js option:selected").val() + ')"/></p>');
                        $("#id_problem_similar").val($("#id_problem_similar").val() + " " + $("#id_problem_id_js option:selected").val());
                    }
                    $(this).dialog("close");
                }
            }
        });

        $("#id_problem_category_id_js").change(function () {
            $.getJSON("./get_problems_by_category.php", {id: $(this).val(), ajax: 'true'}, function (j) {
                var options = '';
                $.each(j, function (key, val) {
                    options += '<option value="' + j[key].id + '">' + j[key].problem_label + '</option>';
                });
                $("#id_problem_id_js").html(options);
            })
        });

        $('#problem-similar-button').on('click', function () {
            $("#dialog").dialog("open");
        });
    }

    if ($('#id_language_id_js').length > 0) {
        $("#id_language_id_js").change(function () {
            populateselectinstance();
        });
        populateselectinstance();
    }

    /*---------------------------------
     *  Autocomplete on users to challenge
     *-------------------------------*/

    if ($('#challenge-users').length > 0) {
        var mytable = new Array();

        // Create the dialog box
        $('#challenge-users').dialog({
            height: 505,
            width: 500,
            modal: true,
            autoOpen: false,
            draggable: false,
            buttons: {
                "Défier les utilisateurs": function () {
                    var toChallenge = new Array();

                    $("#challenged-players p").each(function (index) {
                        toChallenge.push($(this).attr("id"));
                    });

                    $.ajax({
                        url: 'challenge_users.php',
                        type: 'POST',
                        data: {
                            action: 'post',
                            lipsid: $("#hiddenLIPSid").val(),
                            problemid: $("#hiddenProblemid").val(),
                            users: toChallenge
                        },
                        success: function (info) {
                            $("#notify.notifySuccess").show();

                            $("#challenged-players p").each(function (index) {
                                if ($("#challenged-users").text() != "") {
                                    $("#challenged-users").html($("#challenged-users").text() + ", " + $(this).text());
                                } else {
                                    $("#challenged-users").html($(this).text());
                                }
                            });
                        }
                    });
                }
            }
        });

        // Get users
        $.ajax({
            url: 'challenge_users.php',
            type: 'POST',
            data: {
                action: 'get',
                problemid: $("#hiddenProblemid").val()
            },
            dataType: 'json',
            success: function (users) {
                $.map(users, function (value, key) {
                    user = new Object();
                    user.label = value;
                    user.id = key;

                    mytable.push(user);
                });
            }
        });

        // Autocomplete
        $("#challenge-user").autocomplete({
            minLength: 1,
            source: mytable,
            select: function (event, ui) {
                if ($("#challenged-players #" + ui.item.id).length == 0) {
                    $("#challenged-players").append('<p id="' + ui.item.id + '">' + ui.item.label + '<img src="images/delete_similar.png" title="Supprimer" onClick="deleteChalengedUser(' + ui.item.id + ')"/></p>');
                }
            }
        });

        // Open the dialog box
        $("#challenge").click(function () {
            $("#challenge-users").dialog("open");

            return false;
        });

        $(document).on('click','.ui-dialog-titlebar-close',function(){
            $("#notify.notifySuccess").hide();
            $("#challenge-user").val('');
            $("#challenged-players").html('');
        });
    }

    /*---------------------------------
     *  Autocomplete on "Profile - Solved problems"
     *-------------------------------*/

    if ($('.solved_problems_ac').length > 0) {
        var mytable = new Array();

        // Get problems
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'solved_problems',
                userid: $("#hiddenUserID").val()
            },
            dataType: 'json',
            success: function (problems) {
                $.map(problems, function (value, key) {
                    problem = new Object();
                    problem.label = value;
                    problem.id = key;

                    mytable.push(problem);
                });
            }
        });

        // Autocomplete
        $(".solved_problems_ac").autocomplete({
            minLength: 1,
            source: mytable
        });
    }

    /*---------------------------------
     *  Autocomplete on "Profile - Followed users"
     *-------------------------------*/

    if ($('.followed_users_ac').length > 0) {
        var mytable = new Array();

        // Get users
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'followed_users',
                userid: $("#hiddenUserID").val()
            },
            dataType: 'json',
            success: function (users) {
                $.map(users, function (value, key) {
                    user = new Object();
                    user.label = value;
                    user.id = key;

                    mytable.push(user);
                });
            }
        });

        // Autocomplete
        $(".followed_users_ac").autocomplete({
            minLength: 1,
            source: mytable
        });
    }

    /*---------------------------------
     *  Autocomplete on "Category - Problems"
     *-------------------------------*/

    if ($('.category_problems_ac').length > 0) {
        var mytable = new Array();

        // Get users
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'problems_by_category',
                categoryid: $("#hiddenCategoryID").val()
            },
            dataType: 'json',
            success: function (problems) {
                $.map(problems, function (value, key) {
                    problem = new Object();
                    problem.label = value;
                    problem.id = key;

                    mytable.push(problem);
                });
            }
        });

        // Autocomplete
        $(".category_problems_ac").autocomplete({
            minLength: 1,
            source: mytable
        });
    }

    /*---------------------------------
     *  Autocomplete on "Problem - Solutions"
     *-------------------------------*/

    if ($('.users_problem_solutions_ac').length > 0) {
        var mytable = new Array();

        // Get users
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'users_problem_solutions',
                problemid: $("#hiddenProblemID").val()
            },
            dataType: 'json',
            success: function (users) {
                $.map(users, function (value, key) {
                    user = new Object();
                    user.label = value;
                    user.id = key;

                    mytable.push(user);
                });
            }
        });

        // Autocomplete
        $(".users_problem_solutions_ac").autocomplete({
            minLength: 1,
            source: mytable
        });
    }

    /*---------------------------------
     *  Autocomplete on "Users"
     *-------------------------------*/

    if ($('.users_ac').length > 0) {
        var mytable = new Array();

        // Get users
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'users'
            },
            dataType: 'json',
            success: function (users) {
                $.map(users, function (value, key) {
                    user = new Object();
                    user.label = value;
                    user.id = key;

                    mytable.push(user);
                });
            }
        });

        // Autocomplete
        $(".users_ac").autocomplete({
            minLength: 1,
            source: mytable
        });
    }

    /*---------------------------------
     *  Autocomplete on "Received challenges - Problems"
     *-------------------------------*/

    if ($('.received_challenges_problems_ac').length > 0) {
        var mytable = new Array();

        // Get problems
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'received_challenges_problems',
                userid: $("#hiddenUserID").val()
            },
            dataType: 'json',
            success: function (problems) {
                $.map(problems, function (value, key) {
                    problem = new Object();
                    problem.label = value;
                    problem.id = key;

                    mytable.push(problem);
                });
            }
        });

        // Autocomplete
        $(".received_challenges_problems_ac").autocomplete({
            minLength: 1,
            source: mytable
        });
    }

    /*---------------------------------
     *  Autocomplete on "Received challenges - Users"
     *-------------------------------*/

    if ($('.received_challenges_users_ac').length > 0) {
        var mytablereceivedu = new Array();

        // Get users
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'received_challenges_users',
                userid: $("#hiddenUserID").val()
            },
            dataType: 'json',
            success: function (users) {
                $.map(users, function (value, key) {
                    user = new Object();
                    user.label = value;
                    user.id = key;

                    mytablereceivedu.push(user);
                });
            }
        });

        // Autocomplete
        $(".received_challenges_users_ac").autocomplete({
            minLength: 1,
            source: mytablereceivedu
        });
    }

    /*---------------------------------
     *  Autocomplete on "Sent challenges - Problems"
     *-------------------------------*/

    if ($('.sent_challenges_problems_ac').length > 0) {
        var mytablesent = new Array();

        // Get problems
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'sent_challenges_problems',
                userid: $("#hiddenUserID").val()
            },
            dataType: 'json',
            success: function (problems) {
                $.map(problems, function (value, key) {
                    problem = new Object();
                    problem.label = value;
                    problem.id = key;

                    mytablesent.push(problem);
                });
            }
        });

        // Autocomplete
        $(".sent_challenges_problems_ac").autocomplete({
            minLength: 1,
            source: mytablesent
        });
    }

    /*---------------------------------
     *  Autocomplete on "Sent challenges - Users"
     *-------------------------------*/

    if ($('.sent_challenges_users_ac').length > 0) {
        var mytablesentu = new Array();

        // Get users
        $.ajax({
            url: 'ajax/autocomplete.php',
            type: 'POST',
            data: {
                action: 'sent_challenges_users',
                userid: $("#hiddenUserID").val()
            },
            dataType: 'json',
            success: function (users) {
                $.map(users, function (value, key) {
                    user = new Object();
                    user.label = value;
                    user.id = key;

                    mytablesentu.push(user);
                });
            }
        });

        // Autocomplete
        $(".sent_challenges_users_ac").autocomplete({
            minLength: 1,
            source: mytablesentu
        });
    }
});