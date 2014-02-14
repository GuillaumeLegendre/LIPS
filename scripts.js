// JavaScript Document

var openingTag = "<code>";
var endingTag = "</code>";

// Create a ace editor
window.createAce = function (editorid, areaid, mode, theme, flag) {

    // Create ace
    var editor = ace.edit(editorid);

    // Set the ace theme
    editor.setTheme("ace/theme/" + theme);

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
   switch(flag){
	   case "configure":
	   		createConfigure(editorid);
	   		break;
		case "code":
			createCode(editorid);
			break;
		case "unit-test":
			createUnitTest(editorid);
			break;
		case "resolution":
			createResolution(editorid);
			//matchTags(editorid);
			break;
		case "readonly":
			createReadOnly(editorid);
		default:
			break;
   }
   
    // Copy the ace content on the area
    if (areaid != null && areaid != '') {
        editor.getSession().on("change", function () {
            $("#" + areaid).val(editor.getSession().getValue());
        });
    }
}


//Method used to determine the position of each starting and ending <code> tag given an Ace editor id
//returns an Array containing tags positionnoing
var matchTags = function (editorid) {

    var editor = ace.edit(editorid);
    var doc = editor.getSession().getDocument();
    var arrayLines = doc.getAllLines();
    /*var patternOpen = /(.*)(<lips-code>)(.*)/m;
     var patternClose = /(.*)(<\/lips-code>)(.*)/m;*/
    var patternOpen = /(.*)(<code>)(.*)/m;
    var patternClose = /(.*)(<\/code>)(.*)/m;

    var matchesOpen = new Array();
    var matchesClose = new Array();
    var matches = new Array();

    for (var i in arrayLines) {

        if (patternOpen.test(arrayLines[i])) {

            var nbOccur = arrayLines[i].split("<code>").length - 1;
            var obj;

            if (nbOccur == 1) {
                obj = {
                    row: parseInt(i),
                    start: arrayLines[i].indexOf("<code>"),
                    end: arrayLines[i].indexOf("<code>") + "<code>".length,
                    state: "opening"
                };

                matchesOpen.push(obj);
            } else {

                var str = arrayLines[i];
                var pos = 0;
                var currentStart = 0;

                for (var j = 0; j < nbOccur; j++) {

                    pos = str.indexOf("<code>", currentStart);
                    currentStart = pos + "<code>".length;
                    obj = {
                        row: parseInt(i),
                        start: pos,
                        end: pos + "<code>".length,
                        state: "opening"
                    };

                    matchesOpen.push(obj);
                }
            }
        }
    }

    for (var i in arrayLines) {

        if (patternClose.test(arrayLines[i])) {

            var nbOccur = arrayLines[i].split("</code>").length - 1;
            var obj;

            if (nbOccur == 1) {
                obj = {
                    row: parseInt(i),
                    start: arrayLines[i].indexOf("</code>"),
                    end: arrayLines[i].indexOf("</code>") + "</code>".length,
                    state: "ending"
                };

                matchesClose.push(obj);
            } else {

                var str = arrayLines[i];
                var pos = 0;
                var currentStart = 0;

                for (var j = 0; j < nbOccur; j++) {

                    pos = str.indexOf("</code>", currentStart);
                    currentStart = pos + "</code>".length;
                    obj = {
                        row: parseInt(i),
                        start: pos,
                        end: pos + "<code>".length,
                        state: "ending"
                    };

                    matchesClose.push(obj);
                }
            }
        }
    }


    for (var i = 0; i < matchesOpen.length; i++) {
        matches.push(
            {
                opening: matchesOpen[i],
                ending: matchesClose[i]
            }
        );
    }

    //console.log(matches);

    return matches;
}


/*Checks if the cursor position given as parameter is in the tags Array
 returns a Boolean value*/
var inTag = function (tags, row, col) {

    for (var i = 0; i < tags.length; i++) {
        if (row >= tags[i].opening.row && row <= tags[i].ending.row) {
            if (col >= tags[i].opening.end && col <= tags[i].ending.start) {
                return true;
            }
        }
    }

    return false;
}

/*Calculate the distance between the cursor and a tag given as parameter
 returns the following object :
 {
 rowDistance : XXX,
 colDistance : XXX,
 object : XXX
 }
 */
var distance = function (tag, row, col) {

    var rowDistance;
    var colDistance;
    var colStartDistance;
    var colEndDistance;

    if (tag.opening.row == row || tag.ending.row == row) {
        if (col > tag.opening.start && col < tag.opening.end) {
            return {
                rowDistance: 0,
                colDistance: 0,
                object: tag.opening
            }
        } else if (col > tag.ending.start && col < tag.ending.end) {
            return {
                rowDistance: 0,
                colDistance: 0,
                object: tag.ending
            }
        }
    }

    rowDistance = Math.min(Math.abs(row - tag.opening.row), Math.abs(row - tag.ending.row));
    colStartDistance = Math.min(Math.abs(col - tag.opening.start), Math.abs(col - tag.opening.end));
    colEndDistance = Math.min(Math.abs(col - tag.ending.start), Math.abs(col - tag.ending.end));
    colDistance = Math.min(colStartDistance, colEndDistance);

    var obj = (colDistance == colStartDistance) ? tag.opening : tag.ending;

    return {
        rowDistance: rowDistance,
        colDistance: colDistance,
        object: obj
    }
}


/*Searches the closest tag from the cursor given as parameter
 the purpose is to set the cursor into the <code></code> tags in order to restrain edition
 */
var closestTag = function (tags, row, col) {

    var score = Number.MAX_VALUE;
    var closestIndex = 0;
    var closest = new Array();
    var rowDistance = Number.MAX_VALUE;

    for (var i = 0; i < tags.length; i++) {
        var obj = distance(tags[i], row, col);
        closest.push(obj);
    }

    closest.sort(function (a, b) {
        return a.rowDistance - b.rowDistance;
    });

    var rowMinValue = closest[0].rowDistance;
    var colMinValue = closest[0].colDistance;

    for (var i = 0; i < closest.length; i++) {
        if (closest[i].rowDistance > rowMinValue) break;
        if (closest[i].colDistance < colMinValue) {
            colMinValue = closest[i].colDistance;
            closestIndex = i;
        }
    }

    return closest[closestIndex];
}


//Create an ace editor in resolution mode
//used to solve problem. allows user to type only betweens <code></code> tags
window.createResolution = function(editorid){
	
	var editor = ace.edit(editorid);
	var selection = editor.getSession().getSelection();
	var cursor = selection.getCursor();
	var selectionHandler = function(){
		
		var cursor = selection.getCursor();
		var tags = matchTags(editorid);
		
		if (!inTag(tags, cursor.row, cursor.column)){
			var tag = closestTag(tags, cursor.row, cursor.column);
			if (tag.object.state == "opening")
				selection.selectToPosition({row : tag.object.row, column : tag.object.end});
			else if (tag.object.state == "ending")
				selection.selectToPosition({row : tag.object.row, column : tag.object.start});
				
			selection.clearSelection();
		}
		
		
	}
	
	//selection.on("changeCursor", selectionHandler);
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
window.createCode = function (editorid) {

    // Create ace
    var editor = ace.edit(editorid);

    $("#" + editorid).before('<div class="acepanel">' +
        '<a href="#" id="' + editorid + '_tagCode">Code</a>' +
        '</div>');

    // Click on the code tag
    $("#" + editorid + "_tagCode").click(function () {
        if (editor.findAll('Code', null, true) == 0)
            editor.insert("<lips-code/>");
        editor.focus();

        return false;
    });
}

//Create an ace editor in unit-test mode
//used for choosing the unit test to display, provides unit-test tag
window.createUnitTest = function (editorid) {

    // Create ace
    var editor = ace.edit(editorid);

    $("#" + editorid).before('<div class="acepanel">' +
        '<a href="#" id="' + editorid + '_tagUnit">Unit Test</a>' +
        '</div>');

    // Click on the code tag
    $("#" + editorid + "_tagUnit").click(function () {

        if (editor.getCopyText().length == 0) {
            editor.insert("<lips-unit-test></lips-unit-test>");
        } else {
            var currentText = editor.getCopyText();
            editor.remove(editor.getSelectionRange());
            editor.insert("<lips-unit-test>" + currentText + "</lips-unit-test>");
        }

        return false;
    });
}

//Create an ace editor in readonly mode
//used to display problem's code. not editable
window.createReadOnly = function(editorid){
	var editor = ace.edit(editorid);
	var doc = editor.getSession().getDocument();
	var lines = doc.getLength();
	var allLines = doc.getAllLines();
	var longestLine = 0;
	var longestIndex = 0;
	editor.setReadOnly(true);
	editor.renderer.setShowGutter(false);
	editor.setHighlightActiveLine(false);
	
	for (var i in allLines) {
		if (longestLine < allLines[i].length){
			longestLine = allLines[i].length;
			longestIndex = i;
		}
	}
	
	for (var j in allLines[longestIndex]) {
		if (allLines[longestIndex][j] == "\t")
			longestLine = longestLine + 3;
	}
	
	$("#" + editorid).css("height", lines * 16);
	$("#" + editorid).css("width", (20 + (longestLine * 7)));
}

$(document).ready(function () {

    /*---------------------------------
     *  Ajax to populate select of similar problem
     *-------------------------------*/

    if ($('#dialog').length > 0){
        $('#dialog').dialog({
            height: 250,
            width: 400,
            modal: true,
            autoOpen: false,
            draggable: false,
            buttons: {
                Conseiller: function () {
                    if ($.inArray($("#id_problem_id_js option:selected").val(), $("#id_problem_similar").val().split(" ")) == -1) {
                        $("#problem_similar_content").append("<div class='fitem fitem_ftext similar_problem'><div class='felement ftext'><input readonly type='text' name='select_problem_similar_" + $("#id_problem_id_js option:selected").val() + "' value='" + $("#id_problem_id_js option:selected").text() + "'></div> <input class='delete_problem_similar_button' id='"+$("#id_problem_id_js option:selected").val()+"' src='./images/delete_similar.png' type='image'></div>");
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

        $('#problem_similar_button').on('click', function () {
            $("#dialog").dialog("open");
        });

        $('body').on('click', '.delete_problem_similar_button', function () {
            $(this).parent().remove();
            $("#id_problem_similar").val($("#id_problem_similar").val().replace(" "+$(this).attr("id"),""));
        });
    }
});