// JavaScript Document
/*
to set Ace highlighting mode
*/
window.setAceMode = function(mode){
	editor.getSession().setMode('ace/mode/' + mode);
}

$(document).ready(function(){
	
	/*
	adds control over the text editor : it adds lips specific tags
	*/
	$("#editor").before('	<div id="editorpanel">' + 
										'<a href=\"#\" id=\"import\" >&lt;lips-preconfig-import/&gt;</a>' + 
										'<a href=\"#\" id=\"code\" >&lt;lips-preconfig-code/&gt;</a>' +
										'<a href=\"#\" id=\"tests\" >&lt;lips-preconfig-tests/&gt;</a>' +
									  '</div>');
	
	
	$("#editorpanel").css("background-color", "#DDD");	
	$("#editorpanel a").each(function(index){
		$(this).css("margin-right", 20);
	});
	
	
	/*
	click handlers for lips tags
	*/
	$("#import").click(function(){
		
		if (editor.findAll('<lips-preconfig-import/>', null, true) == 0)
			editor.insert("<lips-preconfig-import/>");
	});
	
	$("#code").click(function(){
		
		if (editor.findAll('<lips-preconfig-code/>', null, true) == 0)
			editor.insert("<lips-preconfig-code/>");
	});
	
	$("#tests").click(function(){
		
		if (editor.findAll('<lips-preconfig-tests/>', null, true) == 0)
			editor.insert("<lips-preconfig-tests/>");
	});
	
	
});