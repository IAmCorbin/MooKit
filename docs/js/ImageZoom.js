/**
  * @class Creates a thumbnail with a draggable overlay view box. The contents of this box are displayed enlarged in a display container
  * 
  * @author http://www.php-help.ro/mootools-12-javascript-examples/mootools-12-javascript-image-zoom/
  * @author Corbin Tarrant - added Documentation
  *
  * @property 	{element}	zoomSize		thumbnail size * this = display container size
  * @property	{element}	image_full	the image
  * @property 	{element}	image_thumb	the thumbnail
  * @property	{img}		thumbnail	
  */
var ImageZoom = new Class({
					   
	initialize: function(){
		this.zoomSize = 3;
		
		this.image_full = $('zoomer_thumb').getElement('a');
		this.image_thumb = this.image_full.getElement('img');
		
		this.thumbnail = new Asset.image( this.image_thumb.get('src'),{
			onload: function(){
				$('zoomer_thumb').empty();
				this.thumbnail.inject('zoomer_thumb');
				this.generateZoomer( new Hash({ width:this.thumbnail.width , height:this.thumbnail.height }) );
			}.bind(this)
		});		
	},
	/**
	  * @function 
	  * @param {Hash} thumb_size	a hash containing x:thumbnail.width, y:thumbnail.height
	  */
	generateZoomer: function( thumb_size ){
		this.setDimensions('zoomer_thumb',thumb_size.width,thumb_size.height);
		this.setDimensions('zoomer_big_container',thumb_size.width*this.zoomSize,thumb_size.height*this.zoomSize);
		
		this.bigImage = new Asset.image( this.image_full.get('href'), {
			id:'zoomer_image',
			onload: function(){				
				this.bigImage.inject('zoomer_big_container');
				/* determine the proportions between the thumbnail and the zoomed image*/
				var ratioX = this.bigImage.width/thumb_size.width;
				var ratioY = this.bigImage.height/thumb_size.height;
				/* set the size of the zoomed area on thumbnail */
				var regionWidth = (thumb_size.width/ratioX).toInt()*this.zoomSize;
				var regionHeight = (thumb_size.height/ratioY).toInt()*this.zoomSize;				
				new Element('div', {
					id: 'zoomer_region',
					styles: {
						'width': regionWidth,
						'height': regionHeight,
						'opacity': .7
					}
				}).injectInside('zoomer_thumb');
				/* move the zoomed image when the zoomer region is dragged on the thumbnail */
				new Drag('zoomer_region', {
					modifiers: {x: 'left', y: 'top'},
					grid:1,
					limit: {x:[0,(thumb_size.width - regionWidth)], y:[0, (thumb_size.height-regionHeight)]},
					onDrag: function(el){
						/* get the zoomed position on thumbnail */
						var pos = el.getPosition('zoomer_thumb');
						/* calculate where the zoomed image should be positioned */
						var calcLeft = -(pos.x*ratioX);
						var calcTop = -(pos.y*ratioY);
						/* set a few conditions in case the ratio between the thumbnail and the zoomed image is a float number */
						var bigImgLeft = this.bigImage.width - (thumb_size.width*this.zoomSize);
						var bigImgTop = this.bigImage.height - (thumb_size.height*this.zoomSize);						
						var left = (-calcLeft) > bigImgLeft ? -bigImgLeft : calcLeft;
						var top = (-calcTop) > bigImgTop ? -bigImgTop : calcTop;
						/* set the position of the zoomed image according to the position of the zoomed area on thumbnail */
						this.setPosition('zoomer_image',left,top);
					}.bind(this)
				});	
				/* drag directly on the zoomed image. Also updates the zoomed region on the thumbnail */				
				this.DragBig = new Drag('zoomer_image',{
					modifiers: {x:'left',y:'top'},
					grid:1,
					onDrag: function(elem){
						var pos = elem.getPosition('zoomer_big_container');
						var left = pos.x;
						var top = pos.y;
						/* if the zoomed image is dragged outside boundaries, set the correct position */
						if(	pos.x>0 || pos.y>0 || -pos.x > this.bigImage.width-(thumb_size.width*this.zoomSize) || -pos.y > this.bigImage.height-(thumb_size.height*this.zoomSize)){							
							if(pos.x > 0) left = 0;
							if(pos.y > 0) top = 0;
							if ( -pos.x > this.bigImage.width-(thumb_size.width*this.zoomSize) ) left = -1*(this.bigImage.width-(thumb_size.width*this.zoomSize));
							if( -pos.y > this.bigImage.height-(thumb_size.height*this.zoomSize) ) top = -1*(this.bigImage.height-(thumb_size.height*this.zoomSize));
							
							this.setPosition('zoomer_image',left,top);					
						};
						/* moves the zoomed region on thumbnail according to the position of the zoomed image */
						this.setPosition('zoomer_region',-(left/ratioX),-(top/ratioY));						
					}.bind(this)
				})				
			}.bind(this)
		});		
	},
	
	setDimensions: function(element,width,height){
		$(element).setStyles({
			'width':width,
			'height':height
		});
	},
	
	setPosition: function(element,left,top){
		$(element).set({
			styles:{
				'left': left,
				'top':top
			}
		})
	}
})

window.addEvent('domready', function(){
		new ImageZoom();
});