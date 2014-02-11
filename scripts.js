// JavaScript Document

/**
 * Create an ace editor
 *
 * @param string editorid Editor ID
 * @param string areaid Text area ID
 * @param string mode Ace language
 * @param string theme Ace theme
 * @param string flag Ace flag (configure, code or unit-test)
 */
window.createAce = function(editorid, areaid, mode, theme, flag){
	// Create ace
	var editor = ace.edit(editorid);

	// Set the ace theme
	editor.setTheme("ace/theme/" + theme);

	// Set the ace mode
	if(mode != null && mode != '')
		editor.getSession().setMode("ace/mode/" + mode);


	// Switch to call different Ace editor Mode
	switch(flag) {
		case "configure":
			createConfigure(editorid, areaid);
			break;
		case "code":
			createCode(editorid, areaid);
			break;
		case "unit-test":
			createUnitTest(editorid, areaid);
			break;
		default:
			break;
	}

	// Copy the ace content on the area
	editor.getSession().on("change", function() {
		$("#" + areaid).val(editor.getSession().getValue());
	});
}

/**
 * Create the ace panel in configure mode
 *
 * @param string editorid Editor ID
 * @param string areaid Text area ID
 */
window.createConfigure = function(editorid, areaid){

	// Create ace
	var editor = ace.edit(editorid);
	
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
}


/**
 * Create the ace panel in code mode
 *
 * @param string editorid Editor ID
 * @param string areaid Text area ID
 */
window.createCode = function(editorid, areaid){
	
	// Create ace
	var editor = ace.edit(editorid);
	
	$(".ace").before('<div class="acepanel">' +
		'<a href="#" id="' + editorid + '_tagCode">Code</a>' + 
	'</div>');
	
		// Click on the code tag
	$("#" + editorid + "_tagCode").click(function(){
		if (editor.findAll('Code', null, true) == 0)
			editor.insert("<lips-code/>");
		editor.focus();

		return false;
	});
}

/**
 * Create the ace panel in unit test mode
 *
 * @param string editorid Editor ID
 * @param string areaid Text area ID
 */
window.createUnitTest = function(editorid, areaid){
	
	// Create ace
	var editor = ace.edit(editorid);
	
	$(".ace").before('<div class="acepanel">' +
		'<a href="#" id="' + editorid + '_tagUnit">Unit Test</a>' + 
	'</div>');
	
	// Click on the code tag	
	$("#" + editorid + "_tagUnit").click(function(){
		
		if (editor.getCopyText().length == 0) {
			editor.insert("<lips-unit-test></lips-unit-test>");	
		}else{
			var currentText = editor.getCopyText();
			editor.remove(editor.getSelectionRange());
			editor.insert("<lips-unit-test>" + currentText + "</lips-unit-test>");
		}
		editor.focus();
		
		return false;
	});
}

$(document).ready(function() {

	/*---------------------------------
	 *  Style sheets
	 *-------------------------------*/
	$('head').append('<link rel="stylesheet" type="text/css" href="styles.css">');
});