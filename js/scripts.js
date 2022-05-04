document.addEventListener('DOMContentLoaded', function() {
	'use strict';
	if (typeof TOC !== 'undefined') {
		document.getElementById('kts-toc').innerHTML = TOC.toc;
	}
});
