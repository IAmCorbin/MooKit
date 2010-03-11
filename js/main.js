var DEBUG = true;

function debug(input) {
	if(DEBUG)
		console.log(input);
}

window.addEvent('domready', function() {
	
	if($('LOGGEDIN')) {
		authArea = $$('.authArea');
		authArea.setStyle('display','block');
	}		
	
	//Sliding Button Animation
	$$('.slideBtn').each(function(btn) {
		var prev = btn.getPrevious('a').set('tween',{ duration: 200 });
		var span = prev.getElement('span');
		btn.addEvents({
			//slide out
			mouseenter: function(e) { 
				prev.tween('width',245);
				span.fade('in');
			},
			//slide back in
			mouseleave:function(e) {
				prev.tween('width',70);
				span.fade('out');
			}
		});
	});

}); //END DOMREADY EVENT


