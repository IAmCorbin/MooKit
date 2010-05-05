window.addEvent('domready', function() {

	//Add Links Table Sorting and Pagination	
	new SortingTable( 'links', {
		paginator: new PaginatingTable( 'links', 'links_pagination', { per_page: 5 } )
	});
	
	//grab links
	links = $('links').getElement('tbody').getElements('tr');
	//Link Events
	links.addEvent('click',function(e) {
		//Link Delete Event
		if(e.target.className == "adminDeleteLink") {
			//grab link_id, name, and href
			var link_id = e.target.getParent().getFirst();
			var name = link_id.getNext();
			var href = name.getNext();
			new ConfirmBox({
				back: "#F00",
				boxMSG: "Are you sure you want to delete the link '"+name.get('html')+" - "+href.get('html')+"'?",
				onConfirm: function() {
					new Request({
						url: 'codeCore/php/secure/adminDeleteLink.php',
						method: 'post',
						onSuccess: function(response) {
							json = handleResponse(response);
							if(!json) return;
							link_id.getParent().destroy();
						}
					}).send('link_id='+link_id.get('html'));
				}
			});
		//Link Editing Event
		//make sure this is not a cell in the sublink subtable
		} else if(e.target.getParent().getParent().getParent().id =="links") {
			//Add class to flag which row to update when complete
			e.target.getParent().addClass('EDITING');
			//Create Link Edit Form
			new Element('div',{ 
				id: "adminEditingLink",
				styles: { position: 'fixed', left: '0', top: '0', width: '100%', height: '100%', display: 'none', background: '#000', zIndex: '5000' }
			}).inject(document.body);
			//Link Editing Lightbox Content
			var editingContent = new Element('div', {
				class: "adminEditingLinkContent",
				styles: { position:'fixed', left: '20%', top: '10px', width: '600px', height: '250px', padding: '10px', border: 'solid black 8px', background: '#FFF', zIndex: '5001', display: 'none' }
			});
			//close button
			new Element('div', { id: "adminEditingLinkClose", styles: { cursor: 'pointer', background: 'red', width: '25px', height: '25px' }, html: 'Cancel' }).inject(editingContent);
			//get link data from the event
			var link_id = e.target.getParent().getFirst();
			var name = link_id.getNext();
			var href = name.getNext();
			var desc = href.getNext();
			var weight = desc.getNext();
			var ajaxLink = weight.getNext();
			var menuLink = ajaxLink.getNext();
			var access_level = menuLink.getNext();
			//setup data for display
			var name = name.get('html');
			var href = href.get('html');
			var desc = desc.get('html');
			var weight = weight.get('html');
			//checkboxes
			if(ajaxLink.get('html').toInt()) ajaxLink='checked="yes"'; else ajaxLink='';
			if(menuLink.get('html').toInt()) menuLink='checked="yes"'; else menuLink=''; 
			//pick access_level select option
			if(access_level.get('html').toInt() ==  0) access_none="SELECTED"; else access_none="";
			if(access_level.get('html').toInt() ==  1) access_basic="SELECTED"; else access_basic="";
			if(access_level.get('html').toInt() ==  2) access_create="SELECTED"; else access_create="";
			if(access_level.get('html').toInt() ==  4) access_admin="SELECTED"; else access_admin="";
			//The Form Itself
			new Element('form', {
				id: "adminEditLinkForm",
				class: "adminEditLink",
				action: "codeCore/php/secure/adminEditLink.php",
				method: "post",
				html: '<h1>Edit Link</h1>\
						<label>\
							<span>Link Name</span>\
							<input name="name" type="text" size="20" value="'+name+'" />\
						</label>\
						<label>\
							<span>href</span>\
							<input name="href" type="text" size="40" value="'+href+'" />\
						</label>\
						<label>\
							<span>Description</span>\
							<input name="desc" type="text" size="20" value="'+desc+'" /><- Optional: \
						</label>\
						<label style="float: left;">\
							<span>Weight</span>\
							<input name="weight" type="text" size="5" value="'+weight+'" />\
						</label>\
						<label style="float: right;">\
							<span>Ajax link?</span>\
							<input name="ajaxLink"  '+ajaxLink+' value="1" type="checkbox" />\
						</label>\
						<label style="float: right;">\
							<span>Menu link?</span>\
							<input name="menuLink"  '+menuLink+' value="1" type="checkbox" />\
						</label>\
						<label style="float: right;">\
							<span>Access Level</span>\
							<select name="access_level">\
								<option '+access_none+' value="0">NONE</option>\
								<option '+access_basic+' value="1">BASIC</option>\
								<option '+access_create+' value="2">CREATE</option>\
								<option '+access_admin+' value="4">ADMIN</option>\
							</select>\
						</label>\
						<label style="clear: both;" >\
							<input type="hidden" name="link_id" value="'+link_id.get('html')+'" />\
							<input id="adminEditLinkSubmit" type="submit" value="update" />\
						</label>'
			}).inject(editingContent);
			//inject all that into the document
			editingContent.inject(document.body);
			//Apply Lightbox Functionality and trigger it to open
			var editingLightbox = new LightBox('adminEditingLink', {
				onShow: function() { 
					
				},
				onHide: function() { 
					//remove editing flag
					var updateRow = links.getParent().getElement('.EDITING')[0];
					updateRow.removeClass('EDITING');
					this.content.setStyle('display','none');
					this.fadeLightbox.delay('200',this);
				},
				onRemove: function() {
					this.lightbox.destroy();
					this.content.destroy();
				}
			});
			editingLightbox.trigger();
			//Add Update Button Event
			$('adminEditLinkForm').addEvent('submit', function(e) {
				e.stop();
				this.set('send',{
					onSuccess: function(response) {
						json = handleResponse(response);
						if(!json) return;
						if(json.status == "1") {
							//update table row
							var updateRow = links.getParent().getElement('.EDITING')[0];
							updateRow.getChildren('td[name="name"]').set('html',json.name);
							updateRow.getChildren('td[name="href"]').set('html',json.href);
							updateRow.getChildren('td[name="desc"]').set('html',json.desc);
							updateRow.getChildren('td[name="weight"]').set('html',json.weight);
							updateRow.getChildren('td[name="ajaxLink"]').set('html',json.ajaxLink);
							updateRow.getChildren('td[name="menuLink"]').set('html',json.menuLink);
							updateRow.getChildren('td[name="access_level"]').set('html',json.access_level);
							//close lightbox
							editingLightbox.trigger();
						}
					}.bind(this)
				}).send();
			});
		}
	});
	//***************//
	//Sublink Events//
	//***************//
	//Adding Sublinks
	addSublink = $$('.adminAddSublink');
	addSublink.addEvents({
		click: function(e) {
			e.target.set('value','');
		},
		submit: function(e) {
			e.stop();
		},
		keydown: function(e) {
			if(e.key == "enter") {
				//get X and Y positioning of the input
				LOC = getXY(e.target);
				new Request({
					url: 'codeCore/php/secure/adminGetLinks.php',
					method: 'post',
					onRequest: function() {
						//add loading graphic
						e.target.addClass('loadingW');
					},
					onSuccess: function(response) {
						json = handleResponse(response);
						//if no results were found, clear the box and return
						if(!json) {
							e.target.set('value','No Results'); 
							e.target.removeClass('loadingW');
							return;
						} 
						//create the results box
						var sublinkResults = new Element('ul',{
							id: 'sublinkSearchResults',
							styles: {
								position: 'absolute',
								height: e.target.offsetHeight*3,
								left: LOC.X,
								top: LOC.Y+e.target.offsetHeight+2,
								background: '#AAA',
								border: 'outset 2px #FFF',
								cursor: 'pointer',
								overflow: 'auto'
							},
							events: {
								//handle adding, this will be caught from the bubbling event 'click' from the li we add
								click: function(e) {
									if(e.target.className=="sublinkAdd" || e.target.getParent().className=="sublinkAdd") {
										//grab the data from the DOM
										if(e.target.className=="sublinkAdd")
											var link = e.target.getParent().getParent().getParent().getParent();
										else if(e.target.getParent().className=="sublinkAdd")
											var link = e.target.getParent().getParent().getParent().getParent().getParent();
										
										var link_id = link.getFirst().get('html');
										if(e.target.className=="sublinkAdd")
											var sublink = e.target;
										else if(e.target.getParent().className=="sublinkAdd")
											var sublink = e.target.getParent();
										var sublink_id = sublink.getElement('span').get('html');
										var sublink_name = sublink.getFirst('a').get('html');
										var sublink_href = sublink.getFirst('a').getNext().get('html');
										var sublink_desc = sublink.getLast('a').get('html');
										var sublinkTable = e.target.getParent('td[name="sublinks"]').getElement('table').getElement('tbody')
										new Request({
											method: 'post',
											url: 'codeCore/php/secure/adminAddSublink.php',
											onSuccess: function(response) {
												json = handleResponse(response);
												if(json) {
													//add sublink row
													newSublinkRow = new Element('tr',{ class: "sublinkRow" });
													sublinkID = new Element('td', { 
														styles: { display: 'none' }, 
														html: sublink_id,
													});
													new Element('td', { html: sublink_name }).inject(newSublinkRow);
													new Element('td', { html: sublink_href }).inject(newSublinkRow);
													new Element('td', { html: sublink_desc }).inject(newSublinkRow);
													//attempt to clone the events of an existing sublink row - otherwise will need to reload this area to attach the delete event
													var cloneAttempt = sublinkTable.getElement('tr[class="sublinkRow"]');
													if(cloneAttempt)
														newSublinkRow.cloneEvents(cloneAttempt);
													//inject row into this link sublink table
													newSublinkRow.inject(sublinkTable);
													//remove the results box
													e.target.getParent('ul').destroy();
												} else { //Error Adding sublink
													e.target.getParent('form').getElement('input').set('html','Error Adding Sublink');
													//remove the results box
													e.target.getParent('ul').destroy();
												}
											}
										}).send('link_id='+link_id+"&sublink_id="+sublink_id);
									}
								}
							}
						});
						debug(json);
						//add the results
						json.each(function(row) {
							var sublinkResult = new Element('li',{
								class: 'sublinkAdd',
								html: "<a>"+row.name+"</a> - <a>"+row.href+"</a> <a>("+row.desc+")</a>"
							});
							//add the link id as a hidden element
							new Element('span', {
								styles: {
									display: 'none'
								},
								html: row.link_id
							}).inject(sublinkResult);
							//add the result to the results box
							sublinkResult.inject(sublinkResults);
						});
						//Cancel Button
						new Element('div', {
							styles: {
								background: 'red',
								width: '30px',
								height: '10px',
								color: 'black',
								float: 'right'
							},
							html: ' X ',
							events: {
								click: function(e) {
									e.stop();
									//CLOSE
									e.target.getParent('ul').destroy();
								}
							}
						}).inject(sublinkResults,"top");
						//add the results box to the document
						sublinkResults.inject(e.target.getParent());
						//remove loading graphic
						e.target.removeClass('loadingW');
						e.target.set('html','');
					}
				}).send('name='+e.target.value+'&rType=json&notSubs=true');	
			}
		}
	});
	//Delete Sublinks
	//grab all sublink rows
	var sublinks = $$('.sublinkRow');
	sublinks.each(function(element) {
		element.addEvents({
			//right click options
			contextmenu: function(e) {
				e.stop();
				if(e.target.tagName == "TD") {
					var link = e.target.getParent().getParent().getParent().getParent().getParent();
					var link_id = link.getFirst().get('html');
					var sublink = e.target.getParent();
					var sublink_id =  sublink.getFirst().get('html');
					new ConfirmBox({
						boxMSG: 'Are you sure that you no longer want "'+sublink.getFirst().getNext().get('html')+'" to be a sublink of "'+link.getFirst().getNext().get('html')+'"?',
						back: '#F00',
						onConfirm: function() {
							//Delete The Sublink Table Entry
							new Request.HTML({
								method: 'post',
								url: 'codeCore/php/secure/adminDeleteSublink.php',
								onSuccess: function(r1,r2,r3) {
									if(r3 == '1')
									//remove sublinks row
									sublink.destroy();
								}
									
							}).send('link_id='+link_id+"&sublink_id="+sublink_id);
						}
					});
				}
			}
		});
	});
});