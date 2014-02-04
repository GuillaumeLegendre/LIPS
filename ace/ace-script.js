// JavaScript Document
$(document).ready(function(){
	
	$("#editor").before('	<div class=\"test\">' + 
										'<a href=\"#\" id=\"import\" >&lt;lips-preconfig-import/&gt;</a>' + 
										'<a href=\"#\" id=\"code\" >&lt;lips-preconfig-code/&gt;</a>' +
										'<a href=\"#\" id=\"tests\" >&lt;lips-preconfig-tests/&gt;</a>' +
									  '</div>');
	
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