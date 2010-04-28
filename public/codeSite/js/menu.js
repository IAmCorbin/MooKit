window.addEvent('domready', function() {

	//Setup Ajax Links	
	var links = $$('.ajaxLink');
	links.each(function(link) {
		link.addEvent('click',function(e) {
			e.stop();
			window.location.hash = link.get('href');
		});
	});

	$$('.linkDesc').setStyle('display','none');
});