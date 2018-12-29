'use strict';

$(function(){
	if(hljs){
		$('code.html.full, code.css.full, figure code.html').each(function(i, block) {
			hljs.highlightBlock(block);
		});
	}
});
