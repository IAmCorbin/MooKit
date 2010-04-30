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
		fadeDelay: 200
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
				sublink = e.target.getParent().getLast();
				debug("links : mouseenter");
				if(!sublink.retrieve('display')) {
					debug("|| links : mouseenter : STORE display "+sublink.retrieve('displayID'));
					sublink.store('display',1);
					debug("|| links : mouseenter : STORED display "+sublink.retrieve('displayID'));
					this.displaySublinks(e.target.getParent()); 
				}
			}.bind(this), 
			//Mouse Move Display if Hidden
			mousemove: function(e) {
				sublink = e.target.getParent().getLast();
				if(!sublink.retrieve('mmDisplay')) {
					debug("|| links : mousemove : STORE mmDisplay | "+sublink.retrieve('mmDisplay'));
					sublink.store('mmDisplay',1);
					debug("|| links : mousemove : STORED mmDisplay | "+sublink.retrieve('mmDisplay'));
					(function() { 
						if(sublink.getStyle('display') == 'none') {
							this.displaySublinks(e.target.getParent());
						}
					}.bind(this)).delay(this.options.fadeDelay);
				}
				
			}.bind(this),
			//vanish
			mouseleave: function(e) { 
				debug("links : mouseleave"); 
				var sublinks = e.target.getParent().getElements('.sublink');
				//setup to fade links
				var sublinkDestructor = (function() { 
					debug(this);
					this.hideSublinks(sublinks);
				}.bind(this)).delay(this.options.fadeDelay);
				//store id on the last sublink so we can cancel fading
				sublinks.getLast().store('destructorID',sublinkDestructor);
				debug("STORING destructorID "+sublinkDestructor);
			}.bind(this)
		});
		//ADD SUBLINK EVENTS
		this.sublinks.addEvents({
			mouseenter: function() {
				debug('sublink - mouseenter');
				//cancel vanishing
				var sublink = this.getParent().getParent().getLast();
				var sublinkDestructor = sublink.retrieve('destructorID');
				debug("CLEAR desctortorID"+sublinkDestructor);
				$clear(sublinkDestructor);
				sublink.erase('destructorID');
			},
			mouseleave: function(e) {
				debug('sublink - mouseleave');
				debug(e.target);
				var sublinks = e.target.getParent().getParent().getElements('.sublink');
				//setup to fade links
				var sublinkDestructor = (function() { 
					debug(this);
					this.hideSublinks(sublinks);
				}.bind(this)).delay(this.options.fadeDelay);
				//store id so we can cancel if user's mouse reenters a sublink or parent link
				sublinks.getLast().store('destructorID',sublinkDestructor);
				debug("STORING destructorID "+sublinkDestructor);
			}.bind(this)
		});
	/** 
	  * display the sublinks of passed in link
	  * @param 	{element} 	link		the link you want to display sublinks for
	  */
	},displaySublinks: function(link) {
		debug("~~~~~~~~displaySublinks~~~~~~~~~");
		var sublinks = link.getElements('.sublink');
		//cancel vanishing - clear any anonymous vanishing functions that may be about to trigger hideSublinks
		var sublinkDestructor = sublinks.getLast().retrieve('destructorID');
		debug("CLEAR destructorID : "+sublinkDestructor);
		$clear(sublinkDestructor);
		sublink.eliminate('destructorID');
		//display sublinks
		debug('FADE IN SUBS');
		sublinks.each(function(sublink) {
			//see if Fx.Morph instance has already been created
			var sublinkMorph = sublink.retrieve('morph');
			//create a new Fx.Morph for these sublinks if it doesn't exist
			if(!sublinkMorph) {
				debug("Creating new Fx.Morph for ");
				debug(sublink);
				sublinkMorph = new Fx.Morph(sublink, { 'link': 'chain' });
				sublink.store('morph',sublinkMorph);
			}
			sublinkMorph.start({ 'display':'block'});
			sublinkMorph.start({ 'opacity':'1'});
		});
		debug("~~END~~~displaySublinks~~~~END~~~");
	/**
	  * hide the passed in sublink elements
	  * @var 	{element}[]	sublinks		the sublinks to hide
	  */
	},hideSublinks: function(sublinks) {
		debug("~~~~~~~~~hideSublinks~~~~~~~~~~");
		sublinks.each(function(sublink) {
			var sublinkMorph = sublink.retrieve('morph');
			sublinkMorph.start({ 'opacity':'0'});
			sublinkMorph.start({ 'display':'none'});
		});
		//destroy the reference to this function -- eliminate all flags so we can retrigger sublink display
		sublinks.getLast().eliminate('destructorID');
		debug("ELIMINATE mmDisplay & displayID : "+sublinks.getLast().retrieve('mmDisplay')+"|"+sublinks.getLast().retrieve('displayID'));
		sublinks.getLast().eliminate('mmDisplay');
		sublinks.getLast().eliminate('displayID');
		debug("ELIMINATE mmDisplay & displayID : "+sublinks.getLast().retrieve('mmDisplay')+"|"+sublinks.getLast().retrieve('displayID'));
		debug("~~~~END~~~~hideSublinks~~~~~END~~");
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