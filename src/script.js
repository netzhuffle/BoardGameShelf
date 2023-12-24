/*
 * (c) 2013-2014 Jannis Grimm <jannis@gje.ch>
 * MIT License
 */

var infopanel = document.getElementById('infopanel');
var infoimage = document.getElementById('infoimage');
var infoname = document.getElementById('infoname');
var inforating = document.getElementById('inforating');
var infoyearpublished = document.getElementById('infoyearpublished');
var infoownedexpansions = document.getElementById('infoownedexpansions');

var lastxhr;
var infopanelEventListener = function(e) {
	if (e.target != infoimage && e.target.tagName == 'IMG') {
		if (lastxhr) {
			lastxhr.abort();
		}
		
		var image = e.target;
		infopanel.style.top = (image.offsetTop - 11) + 'px'; // 11px = padding+border
		if (image.offsetLeft < window.innerWidth / 2) {
			infopanel.className = 'left';
			infopanel.style.left = (image.offsetLeft - 11) + 'px'; // 11px = padding+border
			infopanel.style.right = 'auto';
		} else {
			infopanel.className = 'right';
			infopanel.style.left = 'auto';
			infopanel.style.right = (innerWidth - (image.offsetLeft + image.offsetWidth) - 11) + 'px'; // 11px = padding+border
		}
		
		infoname.innerHTML = image.getAttribute('data-name'); // should be changed to .dataset.name when an older IE version supports it
		inforating.innerHTML = image.getAttribute('data-rating');
		infoaverage.innerHTML = image.getAttribute('data-average');
		infoplayers.innerHTML = image.getAttribute('data-players');
		infoplayingtime.innerHTML = image.getAttribute('data-playingtime');
		infoyearpublished.innerHTML = image.getAttribute('data-yearpublished');
		infoownedexpansions.innerHTML = '(loading &hellip;)';
		infoimage.src = image.src;
		infoimage.alt = image.alt;
		infopanel.style.display = 'block';
		e.stopPropagation();
		
		var xhr = new XMLHttpRequest();
		xhr.open('GET', 'src/expansions.php?game=' + image.getAttribute('data-objectid'), true);
		xhr.onreadystatechange = function () {
	        if (xhr.readyState == 4) {
		        infoownedexpansions.innerHTML = xhr.responseText;
	        }
	    };
	    xhr.send(null);
	    lastxhr = xhr;
	}
};
document.body.addEventListener('mouseover', infopanelEventListener, false);
document.body.addEventListener('touchstart', infopanelEventListener, false);