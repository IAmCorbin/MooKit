/**
 * @class
 * @author {@link http://madhatted.com/2008/1/11/the-joy-of-a-minimal-complete-javascript-table-sort}
 * added this documentation May 13, 2010
 * @package MooKit
 * @requires MooTools 1.2 {@link http://mootools.net/}
 * // new PaginatingTable( 'my_table', 'ul_for_paginating', {
 * // per_page: 10, // How many rows per page?
 * // current_page: 1, // What page to start on when initialized
 * // offset_el: false, // What dom element to stick the offset in
 * // cutoff_el: false, // What dom element to stick the cutoff in
 * // details: false // Do we have hidden/collapsable rows?
 * // });
 * //
 * // The above were the defaults. You could also pass an array of paginators
 * // instead of just one.
 * //
 * // Requires mootools Class, Array, Function, Element, Element.Selectors,
 * // Element.Event, and you should probably get Window.DomReady if you're smart.
 * //
 * @property 	{element}	table 			the table to add pagination to 
 * @property	{element}	tbody			the body of the table
 * @property	{array}		paginators		paginator elements
 * @property	{int}		pages			the number of pages
 * @property	{int}		current_page		the current page number
 * @property	{int}		low_limit			low page limit
 * @property	{int}		high_limit		high page limit
 * @property 	{element}	options.offset_el 	offset element
 * @property 	{element} 	options.cutoff_el 	cutoff element
 * @property 	{bool} 		options.details		details switch
 * @property 	{int} 		options.per_page 	number of rows per page
 */
var PaginatingTable = new Class({
  Implements: Options,
  options: {
    per_page: 10,
    current_page: 1,
    offset_el: false,
    cutoff_el: false,
    details: false
  },
/** 
  * @constructor
  * @param 	{element}	table		the table to add pagination to 
  * @param 	{element}	ids			ids of paginators
  * @param	{Array}		options		set options
  */
  initialize: function( table, ids, options ) {
    this.table = $(table);
    this.setOptions(options);
    
    this.tbody = this.table.getElement('tbody');
    
    if (this.options.offset_el)
      this.options.offset_el = $(this.options.offset_el);
    if (this.options.cutoff_el)
      this.options.cutoff_el = $(this.options.cutoff_el);

    this.paginators = ($type(ids) == 'array') ? ids.map($) : [$(ids)];

    if (this.options.details) {
      this.options.per_page = this.options.per_page * 2
    }

    this.update_pages();
  },
/** @function update pages **/
  update_pages: function(){
    this.pages = Math.ceil( this.tbody.getChildren().length / this.options.per_page );
    this.create_pagination();
    this.to_page( 1 );
  },
/** @function change pages **/
  to_page: function( page_num ) {
    page_num = page_num.toInt();
    if (page_num > this.pages || page_num < 1) return;
    this.current_page = page_num;
    this.low_limit = this.options.per_page * ( this.current_page - 1 );
    this.high_limit = this.options.per_page * this.current_page;
    var trs = this.tbody.getChildren();
    if (trs.length < this.high_limit) this.high_limit = trs.length;
    for (var i = 0, j = trs.length; i < j; i++) {
      trs[i].style.display = (this.low_limit <= i && this.high_limit > i) ? '' : 'none';
    }
    this.paginators.each(function(paginator){
      var as = paginator.getElements('a').removeClass('currentPage');
      as[this.current_page].addClass('currentPage');
    }, this);
    if (this.options.offset_el)
      this.options.offset_el.set('text', Math.ceil( this.low_limit / ( this.options.details ? 2 : 1 ) + 1 ) );
    if (this.options.cutoff_el)
      this.options.cutoff_el.set('text', ( this.high_limit / ( this.options.details ? 2 : 1 ) ) );
  },
/** @function go to next page **/
  to_next_page: function() {
    this.to_page( this.current_page + 1 );
  },
/** @function go to previous page **/
  to_prev_page: function() {
    this.to_page( this.current_page - 1 );
  },
/** @function create pagination **/
  create_pagination: function() {
    this.paginators.each(function(paginator){
      paginator.empty();
      this.create_pagination_node( '&#171;', function(evt){
        var evt = new Event( evt );
        this.to_prev_page();
        evt.stop();
        return false;
      }).injectInside( paginator );
      for (var page=1; page <= this.pages; page++){
        this.create_pagination_node( page, function(evt){
          var evt = new Event( evt );
          this.to_page( evt.target.get( 'text' ) );
          evt.stop();
          return false;
        }).injectInside( paginator );
      }
      this.create_pagination_node( '&#187;', function(evt){
        var evt = new Event( evt );
        this.to_next_page();
        evt.stop();
        return false;
      }).injectInside( paginator );
    }.bind( this ));
  },
/** @function create paginatoion node **/
  create_pagination_node: function( text, evt ) {
    var span = new Element( 'span' ).set( 'html', text );
    if (text == '&#171;'){
      var a = new Element( 'a', { 'href': '#', 'class': 'previous-page' }).addEvent( 'click', evt.bind( this ) );
    } else if (text == '&#187;'){
      var a = new Element( 'a', { 'href': '#', 'class': 'next-page' }).addEvent( 'click', evt.bind( this ) );
    } else {
      var a = new Element( 'a', { 'href': '#'}).addEvent( 'click', evt.bind( this ) );
    }
    var li = new Element( 'li' );
    span.injectInside( a.injectInside( li ) );
    return li;
  }

});