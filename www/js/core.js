'use strict';

document.addEventListener('DOMContentLoaded', function() {
	if(hljs){
		var blocks = document.querySelectorAll('code.html.full, code.css.full, figure code.html');
		for (var i=0; i<blocks.length; i++) {
			hljs.highlightBlock(blocks[i]);
		};
	}
});
