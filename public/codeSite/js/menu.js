window.addEvent('domready', function() {

	//Setup Ajax Links	
	var links = $$('.ajaxLink');
	links.each(function(link) {
		link.addEvent('click',function(e) {
			e.stop();
			window.location.hash = link.get('href');
		});
	});
	//add fadein description boxes
	var descs = $$('.linkDesc');
	descs.setStyles({
		display: 'block',
		visibility: 'hidden',
		opacity: '0',
		position: 'absolute',
		padding: '3px',
		background: '#CCC',
		border: 'solid #AAA 2px',
		zIndex: '1000'
	});
	var descFadeSpeed = 1500;
	descs.set('tween', { duration: descFadeSpeed });
	descs.getPrevious().addEvents({
		mouseenter: function() {
			var desc = this.getNext();
			//desc.set('tween', { duration: descFadeSpeed });
			desc.fade(0.75);
		},
		mousemove: function(e) {
			var desc = this.getNext();
			desc.setStyles({
				left: e.page.x+10+'px',
				top: e.page.y-10+'px'
			});
		},
		mouseleave: function() {
			var desc = this.getNext();
			desc.set('tween', { duration: descFadeSpeed/3 });
			desc.fade(0);
		}
	});
});