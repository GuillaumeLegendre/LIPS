// JavaScript Document

// Create a ace editor
window.createAce = function(editorid, areaid, mode, theme){
	// Create ace
	var editor = ace.edit(editorid);

	// Set the ace theme
	editor.setTheme("ace/theme/" + theme);

	// Set the ace mode
	if(mode != null && mode != '')
		editor.getSession().setMode("ace/mode/" + mode);

    // Tag editor
	$(".ace").before('<div class="acepanel">' +
		'<a href="#" id="' + editorid + '_tagImport">Import</a>' +
		'<a href="#" id="' + editorid + '_tagCode">Code</a>' +
		'<a href="#" id="' + editorid + '_tagTests">Tests</a>' +
	'</div>');

	// Click on the import tag
	$("#" + editorid + "_tagImport").click(function(){
		if (editor.findAll('Import', null, true) == 0)
			editor.insert("<lips-preconfig-import/>");
		editor.focus();

		return false;
	});
	
	// Click on the code tag
	$("#" + editorid + "_tagCode").click(function(){
		if (editor.findAll('Code', null, true) == 0)
			editor.insert("<lips-preconfig-code/>");
		editor.focus();

		return false;
	});
	
	// Click on the tests tag
	$("#" + editorid + "_tagTests").click(function(){
		if (editor.findAll('Tests', null, true) == 0)
			editor.insert("<lips-preconfig-tests/>");
		editor.focus();

		return false;
	});

    // Copy the ace content on the area
    editor.getSession().on("change", function() {
    	$("#" + areaid).val(editor.getSession().getValue());
    });
}

$(document).ready(function() {

	/*---------------------------------
	 *  Style sheets
	 *-------------------------------*/
	$('head').append('<link rel="stylesheet" type="text/css" href="styles.css">');
});