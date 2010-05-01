/**
 * @class A Class to enable the display of assigned sub elements...Link -> sublinks
 * @author Corbin Tarrant
 *  ___            ___         __ 
 *    |   /\  |\/| |       __   |_/  |__   -   _
 *  _|_ /  \ |  | |___  |__|  | \  |__|  |  |  |
 * 
 * {@link http://www.IAmCorbin.net }
 * @version 1
 * @birth April 30th, 2010
 * @package MooKit
 * 
 * @requires MooTools 1.2
 * {@link http://mootools.net/}
 *
 * @property 	{element}	linkContainers		all of the containers holding the links that contain sublinks
 * @property 	{element}	sublinkContainers		all of the sublink elements
 */
var SubDisplay = new Class({
	Implements: [Options,Events],
	options: {
		sublinkClass: 'sublink',
		fadeDelay: 100
	/** 
	  * @constructor
	  * @param 	{element}[]	sublinks	all of the sublink containers
	  */
	},initialize: function() {
		//grab elements
		this.sublinkContainers =$$("."+this.options.sublinkClass);
		this.linkContainers = this.sublinkContainers.getParent('.link');
		this.links = this.linkContainers.getFirst('a');
		this.sublinks = this.sublinkContainers.getFirst('a');
		//Initialize Positioning and display
		this.sublinkContainers.setStyle('display','none');
		this.linkContainers.each(function(link) {
			LOC = getXY(link);
			var sublinks = link.getElements('.sublink');
			sublinks.setStyles({
				top: LOC.Y+link.getStyle('lineHeight').toInt()+10+'px',
				left: LOC.X
			});
			var offsetSubs = 0;
			sublinks.each(function(sublink) {
				//setup FX chaining
				sublink.set('morph',{ link:'chain'});
				if(offsetSubs==0) { //don't offset first sublink, just set initial width
					offsetSubs += sublink.getStyle('left').toInt();
				} else {
					offsetSubs +=  sublink.getPrevious().getElement('a').get('text').length*10;
					sublink.setStyle('left', offsetSubs+'px');
				}
			});
		});
		this.sublinkContainers.tween('opacity',0);
		//ADD LINK EVENTS
		this.links.addEvents({
			//Mouse Enter Display
			mouseenter: function(e) { 
				var sublink = e.target.getParent().getLast();
				if(!sublink.retrieve('display')) {
					sublink.store('display',1);
					this.displaySublinks(e.target.getParent()); 
				}
			}.bind(this), 
			//Mouse Move Display if Hidden
			mousemove: function(e) {
				sublink = e.target.getParent().getLast();
				if(!sublink.retrieve('mmDisplay')) {
					sublink.store('mmDisplay',1);
					(function() { 
						if(sublink.getStyle('display') == 'none') {
							this.displaySublinks(e.target.getParent());
						}
					}.bind(this)).delay(this.options.fadeDelay);
				}
				
			}.bind(this),
			//vanish
			mouseleave: function(e) { 
				var sublinks = e.target.getParent().getElements('.sublink');
				this.delayedVanish(sublinks);
			}.bind(this)
		});
		//ADD SUBLINK EVENTS
		this.sublinks.addEvents({
			mouseenter: function() {
				//cancel vanishing
				var sublinkDestructor = this.getParent('span[class="link"]').getElements('span[class="sublink"]').getLast().retrieve('destructorID');
				$clear(sublinkDestructor);
				sublink.eliminate('destructorID');
			},
			mouseleave: function(e) {
				var sublinks = e.target.getParent().getParent().getElements('.sublink');
				this.delayedVanish(sublinks);
			}.bind(this)
		});
	},
	/** 
	  * display the sublinks of passed in link
	  * @param 	{element} 	link		the link you want to display sublinks for
	  */
	displaySublinks: function(link) {
		var sublinks = link.getElements('.sublink');
		//cancel vanishing - clear any anonymous vanishing functions that may be about to trigger hideSublinks
		var sublinkDestructor = sublinks.getLast().retrieve('destructorID');
		$clear(sublinkDestructor);
		sublinks.getLast().eliminate('destructorID');
		//display sublinks
		sublinks.each(function(sublink) {
			sublink.morph({display: 'block'});
			sublink.morph({opacity: '1'});
		});
	},
	delayedVanish: function(sublinks) {
		//setup to fade links
		var sublinkDestructor = (function() { 
			this.hideSublinks(sublinks);
		}.bind(this)).delay(this.options.fadeDelay);
		//store id so we can cancel if user's mouse reenters a sublink or parent link
		sublinks.getLast().store('destructorID',sublinkDestructor);
	},
	/**
	  * hide the passed in sublink elements
	  * @var 	{element}[]	sublinks		the sublinks to hide
	  */
	hideSublinks: function(sublinks) {
		sublinks.each(function(sublink) {
			sublink.morph({opacity: '0'});
			sublink.morph({display: 'none'});
		});
		//eliminate all flags so we can retrigger sublink display
		sublinks.getLast().eliminate('destructorID');
		sublinks.getLast().eliminate('mmDisplay');
		sublinks.getLast().eliminate('displayID');
	}
});

window.addEvent('domready', function() {

	//Setup Ajax Links	
	var links = $$('.ajaxLink');
	links.each(function(link) {
		link.addEvent('click',function(e) {
			e.stop();
			window.location.hash = link.get('href');
		});
	});

	sublinks = new SubDisplay();

	//Description Boxes
	var descs = $$('.linkDesc');
	var descFadeSpeed = 1500;
	descs.set('tween', { duration: descFadeSpeed });
	descs.setStyle('opacity','0');
	//add event to each description's cooresponding link
	descs.getPrevious().addEvents({
		mouseenter: function() {
			var desc = this.getNext();
			desc.set('tween', { duration: descFadeSpeed });
			desc.fade(0.75);
		},
		mousemove: function(e) {
			if(e.target.getParent().hasClass('sublink')) {
				var X = (e.page.x)-(e.page.x)+30+'px';
				var Y =  (e.page.y)-(e.page.y)+30+'px';
			} else {
				var X = e.page.x+10+'px';
				var Y = e.page.y-10+'px';
			}
			var desc = this.getNext();
			desc.setStyles({
				left: X,
				top: Y
			});
		},
		mouseleave: function() {
			var desc = this.getNext();
			desc.set('tween', { duration: descFadeSpeed/3 });
			desc.fade(0);
		}
	});
	
	
});