/* JS Spoiler for CTMB BBCode 
 * (C) Chris Dorman, 2013 - CC-BY-NC 3.0
 */
function openCloseSpoiler() {
	var spoiler = document.getElementById('spoiler');
	if (spoiler.style.display == "none") {
		spoiler.style.display = "block";
	} else {
		spoiler.style.display = "none";
	}
}
