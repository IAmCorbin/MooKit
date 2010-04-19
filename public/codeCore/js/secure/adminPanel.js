window.addEvent('domready', function() {

	$$('.userTest').addEvent('click',function() {
		alert(this.get('html'));
	},this);

});