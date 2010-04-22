/**
 * @class The Lightbox Class creates a layer that can be toggled on and off and contain multiple elements of content
 * @birth Febuary 18th, 2010
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
 * {@link http://mootools.net/}
 * 
 * @package MooKit
 *
 * @property	{Element}  	lightbox 				the lightbox element
 * @property	{bool}	 	hidden				visablility flag
 * @property	{Element}	closeButton		 	the element that closes the lightbox
 * @property	{Element[]}	content 				array of all the content boxes in the lightbox
 *
 * @property	{bool}		options.fade 			Should the lightbox fade or just snap in and out
 * @property	{int}		options.fadeSpeed 		(if fade = true) how long should the lightbox take to fade in and out
 * @property	{int}		options.removeDelay 	Delay before lightbox is removed from display - time starts after fade is complete
 * @property	{$empty}	options.onShow		Fires immediately after lightbox is added to display and fades in
 * @property	{$empty}	options.onHide			Fires immediately after a trigger() call when lightbox is already displayed
 * @property	{$empty}	options.onRemove		Fires when lightbox has been removed from display
 */
var LightBox = new Class({
	Implements: [Options,Events],
	options: {
		fade: true,
		fadeSpeed: '200',
		removeDelay: '0'
		/*
		onShow: $empty,
		onHide: $empty.
		onRemove: $empty
		*/
	},
	/**
	  * @constructor
	  * @param {string} 	lightbox 		the ID of the lightbox
	  * @param {string[]} 	options 		passed in options
	  */
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
	/**
	  * @function used to show or hide the lightbox layer
	  */
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
	/**
	  * @function this will begin the lightbox fade
	  */
	fadeLightbox: function() {
		if(this.options.fade) {
			//fade out lightbox
			this.lightbox.fade(0);
			//fade closebutton with a delay
			(function() { this.closeButton.fade(0); }.bind(this)).delay(this.options.fadeSpeed);
		}
		this.removeLightbox.delay(this.options.removeDelay,this);
	},
	/**
	  * @function removeLightbox: removes lightbox from display
	  */
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

/**
 * @class The ConfirmBox Class creates a LightBox object used to ask the user a simple question before continuing with processing
 * @birth April 22nd, 2010
 * @author Corbin Tarrant
 *  ___            ___         __ 
 *    |   /\  |\/| |       __   |_/  |__   -   _
 *  _|_ /  \ |  | |___  |__|  | \  |__|  |  |  |
 * 
 * @link http://www.IAmCorbin.net
 * @version 1.0
 *
 * @requires MooTools 1.2, LightBox
 * {@link http://mootools.net/}
 *
 * @package MooKit
 * 
 *
 * @property	{bool}		options.name 			The name of the confirmationBox
 * @property	{bool}		options.font 			The Font
 * @property	{bool}		options.fontSize		The Font size
 * @property	{bool}		options.back 			The color of the lightbox
 * @property	{bool}		options.color 			text color
 * @property	{bool}		options.boxBorder 		border style of the box
 * @property	{bool}		options.boxCurved 		Used to add an optional classname to apply a curved style to the box and buttons, defaults to an empty string
 * @property	{bool}		options.boxBack 		background color of the box
 * @property	{bool}		options.boxButtonColor	Box Button color
 * @property	{bool}		options.boxMSG 		The Box Message
 * @property	{bool}		options.boxYES 		The Confirm Button Text
 * @property	{bool}		options.boxNO			The Cancel Button Text
 * @property	{$empty}	options.onDisplay		Event fired when ConfirmBox is displayed
 * @property	{$empty}	options.onConfirm		Event fired when Confirm Button is Clicked
 */
var ConfirmBox = new Class({
	Implements: [Options,Events],
	options: {
		name: 'confirmBox',
		font: 'Monospace',
		fontSize: '23px',
		back: '#AAA',
		color: '#FFF',
		boxBorder: 'solid black 2px',
		boxCurved: '',
		boxBack: '#333',
		boxButtonColor: '#555',
		boxMSG: 'Are You Sure?',
		boxYES: 'YES',
		boxNO: 'NO&nbsp;'
		/*
		onDisplay: $empty,
		onConfirm: $empty
		*/
	},
	/**
	  * @constructor
	  * @param {string[]} 	options 		passed in options
	  */
	initialize: function(options) {
		this.setOptions(options);
		
		//The Background Layer
		new Element('div',{
			id: this.options.name,
			styles: { 
				position: 'fixed', left: '0', top: '0',
				width: '100%', height: '100%',
				display: 'none',
				background: this.options.back,
				zIndex: '10000'
			},
			
		}).inject(document.body);
		//Button Styles
		buttonStyles = new Hash({
			float: 'left',
			fontWeight: 'bold', fontSize: this.options.fontSize, fontFamily: this.options.font,
			cursor: 'pointer',
			border: 'solid black 1px',
			background: this.options.boxButtonColor,
			marginLeft: '30px',
			margin: '20px',
			padding: '10px',
			position: 'absolute', left: '10%', bottom: '0'
		});
		//The Confirm Button
		confirmButton = new Element('span',{
			id: this.options.name+'Confirm',
			styles: buttonStyles,
			class: this.options.boxCurved,
			html: this.options.boxYES
		});
		//The Close Button
		buttonStyles.erase('left');
		buttonStyles.set('right','10%');
		closeButton = new Element('span',{
			id: this.options.name+'Close',
			styles: buttonStyles,
			class: this.options.boxCurved,
			html: this.options.boxNO
		});
		//The Message Box
		confirmBox = new Element('div',{
			class: this.options.boxCurved+' '+this.options.name+'Content',
			styles: {
				position: 'fixed', left: '40%', top: '100px',
				width: this.options.boxMSG.length*this.options.fontSize+'px', height: confirmButton.getStyle('lineHeight').toInt()*5+'px',
				fontFamily: this.options.font, fontSize: this.options.fontSize, color: this.options.color,
				display: 'none',
				border: this.options.boxBorder,
				backgroundColor: this.options.boxBack,
				padding: '10px',
				zIndex: '10001'
			},
			html: '<div>'+this.options.boxMSG+'</div><br />'
		});
		//inject buttons into box
		confirmButton.inject(confirmBox);
		closeButton.inject(confirmBox);
		//add confirmBox to DOM
		confirmBox.inject(document.body);
		//Create the Lightbox
		confirmLB = new LightBox(this.options.name, {
			onShow: function() {
				this.fireEvent('display');
			}.bind(this),
			onHide: function() { 
				this.fadeLightbox.delay('200',this);
			},
			onRemove: function() {
				//destroy confirmation box
				$(this.options.name).destroy();
				$$('.'+this.options.name+'Content').destroy();
			}.bind(this),	
		});
		confirmLB.trigger();
		
		//Add Confirm Button Event
		$(this.options.name+'Confirm').addEvent('click', function() {
			this.fireEvent('confirm');
			confirmLB.trigger();
		}.bind(this));
		
	}
});