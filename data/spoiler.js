/*
 * CTMB - Crazy Tiny Message Board - 2012-2020
 * CTMB (Crazy Tiny Message Board) is a simple, flatfile database message
 * board that is created by Chris Dorman (cddo.cf), 2012-2020
 * CTMB is released under the Creative Commons - BY-NC-SA 4.0 NonPorted license
 *
 * CTMB is released with NO WARRANTY.
 *
 */

function openCloseSpoiler() {
	var spoiler = document.getElementById('spoiler');
	if (spoiler.style.display == "none") {
		spoiler.style.display = "block";
	} else {
		spoiler.style.display = "none";
	}
}
