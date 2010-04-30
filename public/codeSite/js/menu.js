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
 * @property 	{element}	allSublinks		all of the sublink elements
 * @property 	{element}	linksWithSubs		all of the links with sublink elements
 */
var SubDisplay = new Class({
	Implements: [Options,Events],
	options: {
		/*
		onDisplay: $empty,
		onVanish: $empty
		*/
	/** 
	  * @constructor
	  */
	},initialize: function() {
		debug("Initializing SubDisplay");
		//grab elements
		this.allSublinks =$$('.sublink');
		this.linksWithSubs = this.allSublinks.getParent('.link');
		//Initialize Positioning and display
		this.allSublinks.setStyle('display','none');
		this.linksWithSubs.each(function(link) {
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
		this.allSublinks.tween('opacity',0);
		//sublink events
		var links = this.allSublinks.getParent().getFirst('a');
		links.addEvents({
			//display
			mouseenter: function(e) { 
				debug("links : mouseenter"); 
				this.displaySublinks(e.target); 
			
			}.bind(this), 
			mousemove: function(e) {
				//display fix
				(function() { if(e.target.getParent().getElement('.sublink').getStyle('display') == 'none') {
					this.displaySublinks(e.target);
				}}.bind(this)).delay(1000);
			}.bind(this),
			//vanish
			mouseleave: function() { debug("links : mouseleave"); 
				var sublinks = this.getParent().getElements('.sublink');
				var sublinkDestructor = (function() { debug('FADE OUT SUBS - SET IN LINKS');
									debug("------------------------------");
									sublinks.each(function(sublink) {
										sublinkMorph = sublink.retrieve('morph');
										sublinkMorph.start({ 'opacity':'0'});
										sublinkMorph.start({ 'display':'none'});
									});
									//destroy the reference to this function
									sublinks.getLast().eliminate('destructorID');
								}).delay('1000');
				
				//store id on the last sublink so we can cancel if user's mouse enters a sublink
				sublinks.getLast().store('destructorID',sublinkDestructor);
				debug("STORING "+sublinkDestructor);
			}
		});
		this.allSublinks.getFirst('a').addEvents({
			mouseenter: function() {
				debug('sublink - mouseenter');
				var sublink = this.getParent().getParent().getLast();
				var sublinkDestructor = sublink.retrieve('destructorID');
				debug("CLEAR "+sublinkDestructor);
				//cancel vanishing
				$clear(sublinkDestructor);
				sublink.erase('destructorID');
			},
			mouseleave: function() {
				debug('sublink - mouseleave');
				var sublinks = this.getParent().getParent().getElements('.sublink');
				var sublinkDestructor = (function() { debug('FADE OUT SUBS - set in subs');
									debug("------------------------------");
									sublinks.each(function(sublink) {
										sublinkMorph = sublink.retrieve('morph');
										sublinkMorph.start({ 'opacity':'0'});
										sublinkMorph.start({ 'display':'none'});
									});
									//destroy the reference to this function
									sublinks.getLast().eliminate('destructorID');
								}).delay('1000');
				//store id so we can cancel if user's mouse reenters a sublink
				sublinks.getLast().store('destructorID',sublinkDestructor);
				debug("STORING "+sublinkDestructor);
			}
		});
	},displaySublinks: function(element) {
		debug("displaySublinks called");
		var sublinks = element.getParent().getElements('.sublink');
		//clear vanishing function if it exists
		var sublink = sublinks.getLast();
		var sublinkDestructor = sublink.retrieve('destructorID');
		debug("CLEAR "+sublinkDestructor);
		//cancel vanishing
		$clear(sublinkDestructor);
		sublink.eliminate('destructorID');
		//display sublinks
		debug('FADE IN SUBS');
		sublinks.each(function(sublink) {
			var sublinkMorph = new Fx.Morph(sublink, { 'link': 'chain' });
			sublinkMorph.start({ 'display':'block'});
			sublinkMorph.start({ 'opacity':'1'});			
			sublink.store('morph',sublinkMorph);
		});
	},hideSublinks: function(element) {
	
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