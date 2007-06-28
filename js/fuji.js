Fuji = {
	NS : 'http://fuji.sourceforge.net/ns',
	
	/**
	 * Loads the item with the specified URI using XMLHttpRequest
	 * @param {String} uri The URI to the item
	 */		
	loadItem : function(uri) {
		if (document.getElementById(uri)) {
			Fuji.selectDeckItem(document.getElementById(uri));
		} else {
			Fuji.reloadItem(uri);
		}
// TODO: This is not for all pages, ne?
//       And why only for loadItem, not in reloadItem?
//		document.getElementById('rsslink').href = uri + '.rss';
//		document.getElementById('atomlink').href = uri + '.atom';
	},
	
	/**
	 * Reloads the item with the specified URI, and go up the tree depth nodes
	 * @param {String} uri The URI to the item
	 * @param {Int} depth Number of parents to reload recursively (optional, default: 0 = none)
	 */	
	reloadItem : function(uri, depth) {
		Fuji.Dialogs.newSimpleDialog('loadingDialog');
		if (depth == undefined) depth = 0;
		for (var i=0; i<=depth; i++) {
			Fuji.reloadItemWithChild(uri);
			if (document.getElementById(uri)) {
				if (document.getElementById(uri).hasAttribute('parent')) {
					uri = document.getElementById(uri).getAttribute('parent');
				} else return;
			} else return;
		}
	},
	
	/**
	 * Reloads the item with the specified URI, and when loaded optionally also processes another document
	 * @param {String} uri The URI to the item
	 * @param {XMLDocument} oDocument the document to process additionally (optional)
	 * @param {XMLDocument} sURI the URI of the document to process additionally (optional)
	 */		
	reloadItemWithChild : function(uri, oDocument, sURI) {
		Fuji.Dialogs.newSimpleDialog('loadingDialog');
		var callback =
		{
			success: Fuji.loadSuccess,
			failure: Fuji.loadFailure,
			argument: { uri: uri, oDocument: oDocument, sURI: sURI }
		}
		YAHOO.util.Connect.initHeader('Accept', 'application/x-fuji+xhtml+xml');
		YAHOO.util.Connect.asyncRequest('GET', uri, callback, null);
	},
	
	loadFailure : function(oXMLHttpObject) {
		Fuji.Dialogs.Instances.loadingDialog.hide();
		Fuji.Dialogs.newErrorDialog(oXMLHttpObject);
	},
	
	/**
	 * Submits the form that is passed, and loads the result
	 * @param {String} uri The URI to the item
	 */
	submitItem : function(form) {
		Fuji.Dialogs.newSimpleDialog('loadingDialog');
		var callback =
		{
			success: Fuji.loadSuccess,
			failure: Fuji.loadFailure,
			argument: { uri: form.action, oDocument: undefined, sURI: undefined }
		}
		YAHOO.util.Connect.setForm(form);
		YAHOO.util.Connect.initHeader('Accept', 'application/x-fuji+xhtml+xml');
		YAHOO.util.Connect.asyncRequest(form.method.toUpperCase(), form.action, callback, null);
	},
	
	/**
	 * Handles the response of the loadItem/reloadItem request
	 * @param {Object} oXMLHttpObject The response object
	 */		
	loadSuccess : function(oXMLHttpObject) {
		if (oXMLHttpObject.responseText) {
			var oDocument = oXMLHttpObject.responseXML;
			if (!oDocument.documentElement) //Internet Explorer-hack
			{
				oDocument = new ActiveXObject("Microsoft.XMLDOM");
				oDocument.loadXML(oXMLHttpObject.responseText);
			}
			Fuji.loadDocument(oDocument, oXMLHttpObject.argument.uri);
			if (oXMLHttpObject.argument.oDocument) {
				Fuji.loadDocument(oXMLHttpObject.argument.oDocument, oXMLHttpObject.argument.sURI);
			}
		}
		Fuji.Dialogs.Instances.loadingDialog.hide();
	},
	
	/**
	 * Processes from a fuji:item document into a forum page
	 * @param {XMLDocument} oDocument The document object
	 * @param {String} sURI The document URI
	 */
	loadDocument : function(oDocument, sURI) {
		if (Fuji.Util.hasAttribute(oDocument.documentElement, 'parent')) {
			var sParentURI = oDocument.documentElement.getAttribute('parent');
			if (!document.getElementById(sParentURI)) {
				Fuji.reloadItemWithChild(sParentURI, oDocument, sURI);
				return;
			}
		}
		var oContent = Fuji.Util.getElementsByTagNameNS(oDocument,Fuji.NS,'content')[0];
		var oOldNode = document.getElementById(sURI);
		if (oOldNode) oOldNode.parentNode.removeChild(oOldNode);
		var oTargetDiv = document.createElement('div');
		oTargetDiv.id = sURI;
		for (var i=0; i < oContent.childNodes.length; i++) {
			var test = Fuji.Util.importNode(oContent.childNodes.item(i), true);
			oTargetDiv.appendChild(Fuji.Util.importNode(oContent.childNodes.item(i), true));
		}
		var oTargetId = oDocument.documentElement.getAttribute('loadcontext');
		var oTarget = document.getElementById(oTargetId);
		oTarget.appendChild(oTargetDiv);
		if (Fuji.Util.hasAttribute(oDocument.documentElement, 'parent')) {
			var sParentURI = oDocument.documentElement.getAttribute('parent');
			oTargetDiv.setAttribute('parent', sParentURI);
		}
		Fuji.selectDeckItem(oTargetDiv);
		var oLoadHandler = Fuji.Util.getElementsByTagNameNS(oDocument, Fuji.NS,'loadhandler');
		if (oLoadHandler.length) {
			oLoadHandler = oLoadHandler[0];
			if (oLoadHandler.hasChildNodes()) {
				eval(oLoadHandler.firstChild.nodeValue);
			}
			if (Fuji.Util.hasAttribute(oLoadHandler, 'function')) {
				var onload = oLoadHandler.getAttribute('function');
				eval(onload).apply(oTargetDiv, []);
			}
		}
	},
	
	/**
	 * Makes the given item oElm visible and hides all the other loaded items
	 * @param {Object} oElm The item to make visible
	 */
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
	
	/**
	 * Handles the DELETE request to delete the item (due to problems with YUI)
	 * @param {String} sURI The uri of the item to be deleted
	 * @param {String} sParentURI The uri of the item's parent
	 */
	httpDeleteAndReload : function(sURI, sParentURI) {
		var callback =
		{
			success: function(o) {Fuji.reloadItem(sParentURI, 999); location.hash='#'+sParentURI},
			failure: Fuji.newErrorDialog,
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('DELETE', sURI, callback, null); 
	},
	
	/**
	 * Handles the response of the data request for the edit item dialog
	 * @param {Object} oElm The response of the request
	 */		
	updateEditDialog : function(oElm) {
		var oSource = oElm.responseXML;
		var oContent = oSource.getElementById('content');
		var oEditTitle = document.getElementById('createEditTitle');
		oEditTitle.value = oSource.getElementById('title').innerHTML;
		var oEditContent = document.getElementById('createEditContent');
		oEditContent.value = oContent.innerHTML;		
		if(Fuji.Editor.isEnabled())
		{
			var oEditor = document.getElementById('editor');
			Fuji.Util.moveChildNodes(oContent, oEditor.contentWindow.document.body);
		}
	},
	
	/**
	 * Handles the EDIT request to put the item (due to problems with YUI)
	 */
	editThread : function() {
		var sURI = document.getElementById('editEntryid').value;
		var callback =
		{
			success: function(o) {Fuji.reloadItem(sURI, 999)},
			failure: Fuji.newErrorDialog,
			argument: []
		}
		YAHOO.util.Connect.setForm('editF');
		YAHOO.util.Connect.asyncRequest('PUT', sURI, callback, null); 
	},
	
	/**
	 * Starts the URL change listener
	 */	
	startUrlChangeListener : function() {
		setTimeout(Fuji.urlChangeListener, 300);
	},
	
	/**
	 * Listens for changes to the URL
	 */
	urlChangeListener : function() {
		if (location.hash != Fuji.currentURLHash) {
			Fuji.urlChangeCallback(location.hash);
			Fuji.currentURLHash = location.hash;
		}
		Fuji.startUrlChangeListener();
	},
	
	/**
	 * Loads an item when the URL has changed
	 * @param {String} hash The URI
	 */
	urlChangeCallback : function(hash) {
		var uri = hash.indexOf('#') != -1 ? hash.substr(hash.indexOf('#')+1) : hash;
		Fuji.loadItem(uri);
	},
	
	/**
	 * Initialises items when the page is loaded
	 */
	init : function() {
		YAHOO.util.Dom.removeClass(document.documentElement, 'loading');
		Fuji.reloadItem('menu', 0);
		Fuji.reloadItem('calendar', 0);
		Fuji.loadItem('item/1');
		if (location.hash == '') location.hash='item/2';
		Fuji.startUrlChangeListener();
	},
	
	/**
	 * Boots the AJAX engine from the HTML page
	 */
	boot : function() {
		Fuji.loadItem('.');
	},
	
	/**
	 * Hides the HTML page while loading. Invoked at the end of this script.
	 */
	preBoot : function() {
		// do browser test!
		if (!Fuji.Util.Browser.ie) {
			var style = document.createElement('style');
			style.type = 'text/css';
			style.appendChild(document.createTextNode('.loading body { display: none }'));
			document.documentElement.appendChild(style);
		} else {
			document.write('<style type="text/css">.loading body { display: none }</style>');
		}
		YAHOO.util.Dom.addClass(document.documentElement, 'loading');
	},
	
	/**
	 * Handles the login request
	 */	
	login : function() {
		var callback = {
			success: function(o) {window.location.reload()},
			failure: Fuji.newErrorDialog
		}
		YAHOO.util.Connect.asyncRequest('POST', 'login', callback, null);
	},
	logout : function() {
		var callback = {
			success: function(o) {window.location.reload()},
			failure: Fuji.newErrorDialog
		}
		YAHOO.util.Connect.asyncRequest('POST', 'logout', callback, null);
 	},
	editGroup : function(sURI, rRead, rPost, rEdit, rDelete) {
		document.getElementById('editGroupF').setAttribute('action', sURI);
		document.getElementById('editGroupRead').checked = rRead;
		document.getElementById('editGroupPost').checked = rPost;
		document.getElementById('editGroupEdit').checked = rEdit;
		document.getElementById('editGroupDelete').checked = rDelete;
		Fuji.newDialog('editGroupDialog');
	},
	
	addTags : function(oElm, sTags) {
		oElm.value += sTags;
		oElm.focus();
	},
	
	reloadPlugins : function() {
		var callback = {
			success: function(o) {window.location.reload()},
			failure: Fuji.newErrorDialog
		}
// 		YAHOO.util.Connect.asyncRequest('GET', 'pluginsettings', callback, null);
	},
	
	getRights : function(sURI) {
		var callback = {
			success: function(o)
			{
			    var oDocument = o.responseXML.documentElement;
			    var oTBody = document.getElementById('rightsDialogTb');
			    var oGroups = Fuji.Util.getElementsByTagNameNS(oDocument, Fuji.NS, 'group');
			    for(var i=0; i<oGroups.length; i++)
			    {
			        var oGroup = oGroups.item(i);
			        var sGroupName = oGroup.getAttributeNS(Fuji.NS, 'groupname');
			        var oTr = document.createElement('tr');
			        var oTdName = document.createElement('td');
			        oTdName.appendChild(document.createTextNode(sGroupName));
			        oTr.appendChild(oTdName);
			        var oTdRead = document.createElement('td');
			        var oInputRead = document.createElement('input');
			        oInputRead.setAttribute('type', 'checkbox');
			        oInputRead.setAttribute('name', 'read_' + sGroupName);
			        if(Fuji.Util.getElementsByTagNameNS(oGroup, Fuji.NS, 'read').item(0).getAttributeNS(Fuji.NS, 'allowed') == '1') oInputRead.setAttribute('checked', 'Checked');
			        oTdRead.appendChild(oInputRead);
			        oTr.appendChild(oTdRead);
			        var oTdPost = document.createElement('td');
			        var oInputPost = document.createElement('input');
			        oInputPost.setAttribute('type', 'checkbox');
			        oInputPost.setAttribute('name', 'post_' + sGroupName);
			        if(Fuji.Util.getElementsByTagNameNS(oGroup, Fuji.NS, 'post').item(0).getAttributeNS(Fuji.NS, 'allowed') == '1') oInputPost.setAttribute('checked', 'Checked');
			        oTdPost.appendChild(oInputPost);
			        oTr.appendChild(oTdPost);
  			        var oTdEdit = document.createElement('td');
			        var oInputEdit = document.createElement('input');
			        oInputEdit.setAttribute('type', 'checkbox');
			        oInputEdit.setAttribute('name', 'edit_' + sGroupName);
			        if(Fuji.Util.getElementsByTagNameNS(oGroup, Fuji.NS, 'edit').item(0).getAttributeNS(Fuji.NS, 'allowed') == '1') oInputEdit.setAttribute('checked', 'Checked');
			        oTdEdit.appendChild(oInputEdit);
			        oTr.appendChild(oTdEdit);
			        var oTdDelete = document.createElement('td');
			        var oInputDelete = document.createElement('input');
			        oInputDelete.setAttribute('type', 'checkbox');
			        oInputDelete.setAttribute('name', 'delete_' + sGroupName);
			        if(Fuji.Util.getElementsByTagNameNS(oGroup, Fuji.NS, 'delete').item(0).getAttributeNS(Fuji.NS, 'allowed') == '1') oInputDelete.setAttribute('checked', 'Checked');
			        oTdDelete.appendChild(oInputDelete);
			        oTr.appendChild(oTdDelete);
			        oTBody.appendChild(oTr);
			    }
			},
			failure: Fuji.newErrorDialog
		}
 		YAHOO.util.Connect.asyncRequest('GET', sURI + '/rights', callback, null);
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
	
	/**
	 * Substitutes a missing getSelectedItems-function
	 */
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
	
	/**
	 * Substitutes a missing getElementsByTagNameNS-function
	 */	
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
	
	/**
	 * Substitutes a missing importNode-function
	 */	
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
					// On certain elements, attributes need to be created in one go using IE-specific syntax.
					switch (sNodeName) {
						case 'input':
							oNew = document.createElement('<input type="'+oNode.getAttribute('type')+'">');
							oNode.removeAttribute('type');
						case 'textarea':
							oNew = document.createElement('<textarea type="'+oNode.getAttribute('type')+'">');
							oNode.removeAttribute('type');
						default:
							oNew = document.createElement(sNodeName);
					}
					try {
						for (var i = 0; i < oNode.attributes.length; i++) {
							oNew.setAttribute(oNode.attributes[i].name, oNode.attributes[i].value);
						}
					} catch (e) {
						// If you get an exception here, add the element and attribute to the switch above.
						alert("Error adding attribute to element <" + oNode.nodeName +
							">, attribute: " + oNode.attributes[i].name +
							", value: " + oNode.attributes[i].value);
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
				case Fuji.Util.COMMENT_NODE:
					oNew = document.createComment(oNode.data);
					break;
				default:
					alert('Node type not supported on importNode: '+oNode.nodeType);
					oNew = document.createComment('Nodetype not supported: '+oNode.nodeType);
			}
			return oNew;
		}
	},

	/**
	 * Substitutes a missing hasAttribute-function
	 */		
	hasAttribute : function (oNode, sName) {
		if (oNode.hasAttribute) {
			return oNode.hasAttribute(sName);
		} else {
			for (var i=0; i<oNode.attributes.length; i++) {
				if (oNode.attributes.item(i).nodeName == sName) return true;
			}
			return false;
		}
	},
		
	/**
	 * Finds a item in an array
	 */
	arraySearch : function (aHaystack, oNeedle) {
		for(n = 0; n < aHaystack.length; n++)
		{
			if(aHaystack[n] == oNeedle)
				return n;
		}
		return false;
	},
	
	/**
	 * Moves all childnodes from oSource to oTarget
	 */
	moveChildNodes : function (oSource, oTarget) {
		while(oSource.hasChildNodes())
			oTarget.appendChild(oSource.firstChild);
	},

	/**
	 * Copies all childnodes from oSource to oTarget
	 */
	copyChildNodes : function (oSource, oTarget) {
		aChildNodes = oSource.childNodes;
		for(n = 0; n < aChildNodes.length; n++)
			oTarget.appendChild(aChildNodes[n].cloneNode(true));
	},
	
	/**
	 * Removes all childnodes from oNode
	 */
	removeChildNodes : function (oNode) {
		while(oNode.hasChildNodes())
			oNode.removeChild(oNode.firstChild);
	},
	
	Browser : {
		//Internet Explorer
		ie: 	(document.all && !navigator.userAgent.match(/opera/i) && !navigator.appVersion.match(/mac/i)),
		//Gecko engine newer then 2003-10-01
		gecko:	(window.controllers && parseInt(navigator.productSub) > 20031001),
		//Support for Opera 9
		opera: 	(navigator.userAgent.match(/opera[ \/][9]\./i)),
		//Support for Safari Webkit 420+ (so Nightly / TOT)
		safari:	(parseInt(navigator.userAgent.substr(navigator.userAgent.search(/applewebkit\//i)+12,3))>=420),
		//Check for quirksmode
		quirks:	(document.compatMode)? document.compatMode.indexOf('CSS1Compat') < 0 : true
	}
}

Fuji.Tree = {
	/**
	 * Finds and initialises all trees in the context
	 * @param {Object} oContextNode DOM context node to search in (optional, defaults to ???this???)
	 */
	init : function(oContextNode) {
		if (oContextNode === undefined) oContextNode = this;
		var oTrees = YAHOO.util.Dom.getElementsByClassName('tree', 'ul', oContextNode);
		for (var i=0; i<oTrees.length; i++) {
			Fuji.Tree.initTree(oTrees[i]);
		}
	},
	/**
	 * Initialises a tree list
	 * @param {Object} oTree DOM root node of the tree (<ul class="tree">)
	 */
	initTree : function(oTree) {
		var oTreeLIs = oTree.getElementsByTagName('li');
		for (var i=0; i<oTreeLIs.length; i++) Fuji.Tree.initTreeLI(oTreeLIs.item(i));
	},
	/**
	 * Add listeners to tree list items
	 * @param {Object} oNode Node in the tree
	 * @private
	 */
	initTreeLI : function(oNode) {
		// check whether first child (previous siblings of type element node)
		var oNodeCheck = oNode;
		while ((oNodeCheck = oNodeCheck.previousSibling) != null && oNodeCheck.nodeType != Fuji.Util.ELEMENT_NODE);
		if (oNodeCheck == null) YAHOO.util.Dom.addClass(oNode, 'first-child');
		else YAHOO.util.Dom.removeClass(oNode, 'first-child');
		// check whether last child (following siblings of type element node)
		var oNodeCheck = oNode;
		while ((oNodeCheck = oNodeCheck.nextSibling) != null && oNodeCheck.nodeType != Fuji.Util.ELEMENT_NODE);
		if (oNodeCheck == null) YAHOO.util.Dom.addClass(oNode, 'last-child');
		else YAHOO.util.Dom.removeClass(oNode, 'last-child');
		// check whether a leaf node
		if (!oNode.getElementsByTagName('ul').length) YAHOO.util.Dom.addClass(oNode, 'leaf');
		else YAHOO.util.Dom.removeClass(oNode, 'leaf');
		YAHOO.util.Event.addListener(oNode, 'click', Fuji.Tree.toggleOpenClose);
	},
	/**
	 * Handler to toggle the open/close class (when applicable)
	 * @param {Object} e Event object
	 * @private
	 */
	toggleOpenClose : function(e) {
		if (YAHOO.util.Event.getTarget(e) == this && this.getElementsByTagName('ul').length) {
			if (YAHOO.util.Dom.hasClass(this, 'open')) {
				YAHOO.util.Dom.replaceClass(this, 'open','closed');
			} else {
				YAHOO.util.Dom.replaceClass(this, 'closed', 'open');
			}
		}
		YAHOO.util.Event.stopPropagation(e);
	}
}

Fuji.preBoot();
