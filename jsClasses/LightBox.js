/**
 * The Lightbox Class creates a layer that can be toggled on and off and contain multiple elements of content
 * Febuary 18th, 2010
 * 
 * To use:
 *   1. Create a div on the page with an ID of your choice
 *   2. Create as many content divs as you need with the class name <ID of div created in step 1>Content
 *   3. Create another element on the page with ID of <ID of div created in step 1>Close
 *		Ex:
 *			<div id="lightbox"></div>
 *			<div class="lightboxContent">Place Content Here</div>
 *			<span class="lightboxClose">Close Lightbox</div>
 *   4. In your mootools domready event create a new lightbox passing in the ID of div from step 1 and any of the options desired
 *		Ex:
 *			lightbox = new Lightbox('lightbox');
 *   5. Create a way to set off the initial trigger
 *		Ex:
 *			html : <div id="open"></div>
 *			js: $('open').addEvent('click',function() { lightbox.trigger });
 *   6. Enjoy!
 *
 *			~~~~~~~~~~~~~~~
 *			~~Free for all to use~~
 *			~~~~~~~~~~~~~~~
 *
 * @author Corbin Tarrant
*   ___            ___         __ 
 *    |   /\  |\/| |       __   |_/  |__   -   _
 *  _|_ /  \ |  | |___  |__|  | \  |__|  |  |  |
 * 
 * @link http://www.IAmCorbin.net
 * @version 1.0
 *
 * @requires MooTools 1.2
 */
 /*
/~~~~~~~~~~~~~~~~~
/variables
/    this.lightbox: the lightbox element
/    this.hidden: visablility flag
/    this.closeButton: the element that closes the lightbox
/    this.content: array of all the content boxes in the lightbox
/
/options:
/    fade: (true/false) should the lightbox fade or just snap in and out
/    fadeSpeed: (if fade = true) how long should the lightbox take to fade in and out
/    removeDelay: delay before lightbox is removed from display - time starts after fade is complete
/
/functions:
/    initialize(lightbox: the ID of the lightbox, options) 
/    trigger: used to show or hide the lightbox layer
/    fadeLightbox: this will begin the lightbox fade
/    removeLightbox: removes lightbox from display
/
/events:
/    onShow: fires immediately after lightbox is added to display and fades in
/    onHide: fires immediately after a trigger() call when lightbox is already displayed
/    onRemove: fired when lightbox has been removed from display
/
/
****************************************************************************************/
var LightBox = new Class({
	Implements: [Options,Events],
	options: {
		//how long does it take the lightbox to fade in and out
		fade: true,
		fadeSpeed: '200',
		removeDelay: '0'
		/*
		onShow: $empty,
		onHide: $empty.
		onRemove: $empty
		*/
	},
	initialize: function(lightbox, options) {
		this.setOptions(options);
		//make sure the delay for removing the lightbox takes at least as long as the fade
		this.options.removeDelay = this.options.removeDelay.toInt()+this.options.fadeSpeed.toInt();
		//visability flag - hidden at start
		this.hidden = true;
		//get lightbox and make transparent
		this.lightbox = $(lightbox);
		this.lightbox.set('tween',{duration: this.options.fadeSpeed});
		//if fading fade out initially
		if(this.options.fade)
			this.lightbox.fade(0);
		//lightbox close button
		this.closeButton = $(lightbox+'Close');
		this.closeButton.set('tween',{duration: this.options.fadeSpeed});
		if(this.options.fade)
			this.closeButton.fade(0);
		//set up close butter to trigger
		this.closeButton.addEvent('click',function() { this.trigger() }.bind(this));
		//get lightbox content
		this.content = $$('.'+lightbox+'Content');
		
	},
	trigger: function() { 
		if(this.hidden) {
			//show lightbox and content blocks
			this.lightbox.setStyle('display','block');
			this.closeButton.setStyle('display','block');
			this.content.setStyle('display','block');
			if(this.options.fade) {
				//fade in lightbox
				this.lightbox.fade(.5);
				this.closeButton.fade(1);
			}
			this.fireEvent('show');
			//set visability flag
			this.hidden = false;
		} else {
			this.fireEvent('hide');
		}
	},
	fadeLightbox: function() {
		if(this.options.fade) {
			//fade out lightbox
			this.closeButton.fade(0);
			this.lightbox.fade(0);
		}
		this.removeLightbox.delay(this.options.removeDelay,this);
	},
	removeLightbox: function() {
		//hide lightbox and content
		this.content.setStyle('display','none');
		this.closeButton.setStyle('display','none');
		this.lightbox.setStyle('display','none');
		//set visability flag
		this.hidden = true;
		this.fireEvent('remove');
	}
});