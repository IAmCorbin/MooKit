var DEBUG = true;

window.addEvent('domready', function() {
	//debug box
	$('debugBox').set('tween',{duration: 100});
	$('debugBox').addEvents({
		//raise box on mouseenter
		mouseenter: function() {
			this.tween('height',this.getStyle('height').toInt()+140+'px');
		},
		//lower box on mouseleave
		mouseleave: function() {
			if( this.getStyle('height').toInt() > 140 )
				this.tween('height',this.getStyle('height').toInt()-140+'px');
			else //prevent a negative height value for IE
				this.tween('height','10px');
		},	
		//expand further and stay up if clicked, toggle back when clicked again
		click: function() {
			if(this.getStyle('height').toInt() < 160 )
				this.tween('height','400%');
			else if(this.getStyle('height').toInt() > 160)
				this.tween('height','25%');
		}
	});

}); //END DOMREADY EVENT