document.addEventListener("DOMContentLoaded", function() {
	var articles = document.getElementsByClassName('post'),
		len = articles.length;
	for (var i = 0; i < len; i++) {
		var artc = articles[i],
			img = artc.getElementsByTagName('img')[0],
			banner = artc.getElementsByTagName("div")[0];
		if (!img) {
			banner.style.display = "none";
			continue;
		};	
		banner.style.backgroundImage = 'url(' + img.src + ')';
	}	
}, false);