Fuji = {
	NS : 'http://fuji.sourceforge.net/ns',
	createDialog : null,
	deleteDialog : null,
	editDialog : null,
	preferencesDialog : null,
	loadItem : function(uri) {
		if (document.getElementById(uri)) {
			Fuji.selectDeckItem(document.getElementById(uri));
		} else {
			Fuji.reloadItem(uri);
		}
	},
	reloadItem : function(uri, depth) {
		if (depth != undefined) {
			for (var i=0; i<depth; i++) {
				Fuji.reloadItem(uri);
				if (document.getElementById(uri)) {
					if (document.getElementById(uri).hasAttribute('parent')) {
						uri = document.getElementById(uri).getAttribute('parent');
					} else return;
				} else return;
			}
			return;
		}
		var callback =
		{
			success: Fuji.loadNode,
			failure: function(o) {alert('failure: ',o.argument.uri)},
			argument: { uri: uri }
		}
		YAHOO.util.Connect.asyncRequest('GET', uri, callback, null); 
	},
	loadNode : function(oXMLHttpObject) {
		var oSource = oXMLHttpObject.responseXML;
		if(!oSource.documentElement) //Internet Explorer-hack
		{
			oSource = new ActiveXObject("Microsoft.XMLDOM");
			oSource.loadXML(oXMLHttpObject.responseText);
		}
		var oTargetId = oSource.documentElement.getAttribute('loadcontext');
		var oTarget = document.getElementById(oTargetId);
		var oContent = Fuji.Util.getElementsByTagNameNS(oSource,Fuji.NS,'content')[0];
		if (document.getElementById(oXMLHttpObject.argument.uri)) {
			document.getElementById(oXMLHttpObject.argument.uri).parentNode.removeChild(document.getElementById(oXMLHttpObject.argument.uri));
		}
		var oTargetDiv = document.createElement('div');
		oTargetDiv.id = oXMLHttpObject.argument.uri;
		oTarget.appendChild(oTargetDiv);
		for (var i=0; i < oContent.childNodes.length; i++) {
			oTargetDiv.appendChild(Fuji.Util.importNode(oContent.childNodes.item(i), true));
		}
		if (Fuji.Util.hasAttribute(oSource.documentElement, 'parent')) {
			oTargetDiv.setAttribute('parent',oSource.documentElement.getAttribute('parent'));
		}
		Fuji.selectDeckItem(oTargetDiv);
		var oLoadHandler = Fuji.Util.getElementsByTagNameNS(oSource, Fuji.NS,'loadhandler');
		if (oLoadHandler.length) {
			eval(oLoadHandler[0].firstChild.nodeValue)
		}
	},
	selectDeckItem : function(oElm) {
		for (var i=0; i < oElm.parentNode.childNodes.length; i++) {
			var oDeckItem = oElm.parentNode.childNodes.item(i);
			if (oDeckItem.nodeType == 1) {
				oElm.parentNode.childNodes.item(i).style.display = 'none';
			}
		}
		oElm.style.display = '';
		if (Fuji.Util.hasAttribute(oElm, 'parent')) {
			Fuji.loadItem(oElm.getAttribute('parent'));
		}
	},
	loadMenu : function() {
		var callback =
		{
			success: Fuji.loadNode,
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('GET', 'menu/', callback, null); 
	},
	clearFora : function() {
		var oForaTreeDiv = document.getElementById('foraTreeDiv');
		while(oForaTreeDiv.hasChildNodes())
			oForaTreeDiv.removeChild(oForaTreeDiv.firstChild);
	},
	clearThreads : function() {
		var oThreadsTableDiv = document.getElementById('threadsTableDiv');
		while(oThreadsTableDiv.hasChildNodes())
			oThreadsTableDiv.removeChild(oThreadsTableDiv.firstChild);
	},
	clearPosts : function() {
		var oPostsListDiv = document.getElementById('postsListDiv');
		while(oPostsListDiv.hasChildNodes())
			oPostsListDiv.removeChild(oPostsListDiv.firstChild);
	},
	clearMenu : function() {
		var oMenuListDiv = document.getElementById('menuListDiv');
		while(oMenuListDiv.hasChildNodes())
			oMenuListDiv.removeChild(oMenuListDiv.firstChild);
	},
	reloadFora : function() {

		Fuji.clearFora();
		Fuji.loadFora();
	},
	reloadAll : function() {
		location.hash='#item/2';
		Fuji.reloadItem("item/2");
	},
	newCreateDialog : function(iId, iType) {
		if(!Fuji.createDialog)
		{
			Fuji.createDialog = new YAHOO.widget.Dialog("createDialog", { visible: false, fixedCenter: true });
			var handleCancel = function() {
				this.cancel();
				document.getElementById('createF').reset();			
				this.hide();
			}
			var handleSubmit = function() {
				document.getElementById('createType').value = iType; 
				document.getElementById('createRef').value = iId;
				this.submit();
				document.getElementById('createF').reset();			
				this.hide();
				Fuji.reloadItem("item/" + document.getElementById('createRef').value);
				}
			Fuji.createDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel } ]);
			Fuji.createDialog.cfg.queueProperty("onsuccess", Fuji.reloadThreads);
			Fuji.createDialog.render();
		}
		document.getElementById('createDialog').style.display = 'block';
		Fuji.createDialog.show();
	},
	newDeleteDialog : function(iId, iPId) {
		Fuji.deleteDialog = new YAHOO.widget.SimpleDialog("dlg", { 
			width: "20em", 
			effect:{effect:YAHOO.widget.ContainerEffect.FADE,
		        duration:0.25}, 
			fixedcenter:true,
			modal:true,
			draggable:false });

		Fuji.deleteDialog.setHeader(Fuji.messages.warning);
		Fuji.deleteDialog.setBody(Fuji.messages.sure_delete_item);
		Fuji.deleteDialog.cfg.setProperty("icon", YAHOO.widget.SimpleDialog.ICON_WARN);		
		
		var handleOK = function() {
			Fuji.deleteItem(iId, iPId);
			this.hide();
		}
		var handleCancel = function() {
			this.hide();
		}
		
		Fuji.deleteDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleOK }, { text:Fuji.messages.cancel, handler:handleCancel } ]);
		Fuji.deleteDialog.render(document.body);
	},
	deleteItem : function(iId, iPId) {
		var callback =
		{
			success: function(o) {Fuji.reloadItem('item/' + iPId); location.hash='#item/' + iPId},
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('DELETE', 'item/' + iId, callback, null); 
	},
	newEditDialog : function(iId) {
		if(!Fuji.editDialog)		
		{
			Fuji.editDialog = new YAHOO.widget.Dialog("editDialog", { visible: false, fixedCenter: true, width: '200px' });
			var handleCancel = function() {
				document.getElementById('editF').reset();
				this.cancel();
			}
			var handleSubmit = function() {
				Fuji.editThread();
				this.hide();
			}
			Fuji.editDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel} ]);
			Fuji.editDialog.cfg.queueProperty("onsuccess", Fuji.reloadItem('item/' + iId, 999));
			Fuji.editDialog.render();
		}
		var callback =
		{
			success: Fuji.updateEditDialog,
			failure: function(o) {alert('failure')},
			argument: []
		}
		document.getElementById('editEntryid').value = iId;
		YAHOO.util.Connect.asyncRequest('GET', 'item/' + iId + '/source', callback, null); 		
		document.getElementById('editDialog').style.display = 'block';		
		Fuji.editDialog.show();
	},
	updateEditDialog : function(oElm) {
		var oSource = oElm.responseXML;
		var oEditTitle = document.getElementById('editT');
		var oEditContent = document.getElementById('editContent');
		oEditTitle.value = oSource.getElementById('title').innerHTML;
		oEditContent.value = oSource.getElementById('content').innerHTML;	

	},
	editThread : function() {
		var iId = document.getElementById('editEntryid').value;
		var callback =
		{
			success: function(o) {Fuji.reloadItem('item/' + iId)},
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.setForm('editF');
		YAHOO.util.Connect.asyncRequest('POST', 'item/' + iId, callback, null); 
	},
	startUrlChangeListener : function() {
		setTimeout(Fuji.urlChangeListener, 300);
	},
	urlChangeListener : function() {
		if (location.hash != Fuji.currentURLHash) Fuji.urlChangeCallback(location.hash);
		Fuji.currentURLHash = location.hash;
		Fuji.startUrlChangeListener();
	},
	urlChangeCallback : function(hash) {
		var uri = hash.indexOf('#') != -1 ? hash.substr(hash.indexOf('#')+1) : hash;
		Fuji.loadItem(uri);
	},
	newPreferencesDialog : function() {
		if(!Fuji.preferencesDialog)
		{
			Fuji.preferencesDialog = new YAHOO.widget.Dialog("prefDialog", { visible: false, fixedCenter: true, width: '325px' });
			var handleCancel = function() {
				this.cancel();
			}
			var handleSubmit = function() {
				var oPrefPass1 = document.getElementById('prefPass1');
				var oPrefPass2 = document.getElementById('prefPass2');
				var oPrefEmail = document.getElementById('prefEmail');
				var oPrefPhoto = document.getElementById('prefPhoto');			
				if(oPrefPass1.value != oPrefPass2.value)
					alert(Fuji.messages.passwords_do_not_match);
				else
				{
					if(oPrefEmail.value == '')
						alert(Fuji.messages.email_empty);
					else
					{
						if(oPrefPhoto.value && oPrefPhoto.value.indexOf('http://') != 0)
							alert(Fuji.messages.invalid_url);
						else
						{
							this.submit();
							this.hide();
						}
					}
				}
			}
			Fuji.preferencesDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel} ]);
			Fuji.preferencesDialog.cfg.queueProperty("onsuccess", function() {location.reload();});
			Fuji.preferencesDialog.render();
		}
		document.getElementById('prefDialog').style.display = 'block';
		Fuji.preferencesDialog.show();
	},
	init : function() {
		Fuji.loadMenu();
		Fuji.loadItem('item/1');
		if (location.hash == '') location.hash='#item/2';
		Fuji.startUrlChangeListener();
	}
}
Fuji.Util = {
	// Opera and IE don't have these defined on the nodes themselves :(
	ELEMENT_NODE                : 1,
	ATTRIBUTE_NODE              : 2,
	TEXT_NODE                   : 3,
	CDATA_SECTION_NODE          : 4,
	ENTITY_REFERENCE_NODE       : 5,
	ENTITY_NODE                 : 6,
	PROCESSING_INSTRUCTION_NODE : 7,
	COMMENT_NODE                : 8,
	DOCUMENT_NODE               : 9,
	DOCUMENT_TYPE_NODE          : 10,
	DOCUMENT_FRAGMENT_NODE      : 11,
	NOTATION_NODE               : 12,
	
	getSelectedItems : function(oSelect) {
		var aSelectItems = oSelect.getElementsByTagName('option');
		var selected = "";
		for(var n = 0; n < aSelectItems.length; n++)
			if(aSelectItems[n].selected)
				if(selected == "")
					selected += aSelectItems[n].value;
				else
					selected += "," + aSelectItems[n].value;
		return selected;
	},
	getElementsByTagNameNS : function (oNode, sNs, sLocalName) {
		if (oNode.getElementsByTagNameNS) {
			return oNode.getElementsByTagNameNS(sNs, sLocalName);
		} else {
			var oElements = oNode.getElementsByTagName('fuji:' + sLocalName);
			var aResult = [];
			for (var i=0; i<oElements.length; i++) {
				var oElm = oElements.item(i);
				if (oElm.namespaceURI == sNs && oElm.nodeName == (oElm.prefix + ':' + sLocalName)) {
					aResult.push(oElm);
				}
			}
		}
		return aResult;
	},
	importNode : function (oNode, deep) {
		if(document.importNode) {
			return document.importNode(oNode, deep);
		} else {
			var oNew;
			switch (oNode.nodeType) {
				case Fuji.Util.ELEMENT_NODE:
					if (oNode.prefix) {
						sNodeName = oNode.nodeName.substring(oNode.nodeName.indexOf(':')+1);
					} else {
						sNodeName = oNode.nodeName;
					}
					oNew = document.createElement(sNodeName);
					for(var i = 0; i < oNode.attributes.length; i++){
						oNew.setAttribute(oNode.attributes[i].name, oNode.attributes[i].value);
					}
					if (oNode.style) oNew.style.cssText = oNode.style.cssText;
					if (oNode.getAttribute('style') != null) oNew.style.cssText = oNode.getAttribute('style');
					if (oNode.getAttribute('class') != null) oNew.className = oNode.getAttribute('class');
					
					if(deep && oNode.hasChildNodes()){
						for(var oChild = oNode.firstChild; oChild; oChild = oChild.nextSibling){
							var oImportedChildNode = Fuji.Util.importNode(oChild, true);
							if (oImportedChildNode) oNew.appendChild(oImportedChildNode);
						}
					}
					break;
				case Fuji.Util.TEXT_NODE:
					oNew = document.createTextNode(oNode.nodeValue);
					break;
			}
			return oNew;
		}
	},
	hasAttribute : function (oNode, sName) {
		if (oNode.hasAttribute) {
			return oNode.hasAttribute(sName);
		} else {
			for (var i=0; i<oNode.attributes.length; i++) {
				if (oNode.attributes.item(i).nodeName == sName) return true;
			}
			return false;
		}
	}	
}