Fuji.Dialogs = {
	newDialog : function(sName) {
		if(!Fuji.Dialogs.Instances[sName])
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;
			Fuji.Dialogs.Instances[sName] = new YAHOO.widget.Dialog(sName, { visible: false, fixedCenter: true, effect: effect });
			var buttons = new Array();
			if(Fuji.Dialogs.Settings[sName].handleSubmit)
				buttons.push( { text:Fuji.Messages.ok, handler:Fuji.Dialogs.Settings[sName].handleSubmit, isDefault:true } );
			if(Fuji.Dialogs.Settings[sName].handleCancel)
				buttons.push( { text:Fuji.Messages.cancel, handler:Fuji.Dialogs.Settings[sName].handleCancel } );
			if(buttons.length > 0)
				Fuji.Dialogs.Instances[sName].cfg.queueProperty("buttons", buttons);
			Fuji.Dialogs.Instances[sName].cfg.queueProperty("onsuccess", Fuji.Dialogs.Settings[sName].onSuccess);
			Fuji.Dialogs.Instances[sName].render();
		}
		document.getElementById(sName).style.display = 'block';
		Fuji.Dialogs.Instances[sName].show();		
	},
	
	newSimpleDialog : function(sName) {
		if(!Fuji.Dialogs.Instances[sName])
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;
			Fuji.Dialogs.Instances[sName] = new YAHOO.widget.SimpleDialog(sName, { modal: true, visible: false, fixedCenter: true, effect: effect });
			var buttons = new Array();
			if(Fuji.Dialogs.Settings[sName].handleSubmit)
				buttons.push( { text:Fuji.Messages.ok, handler:Fuji.Dialogs.Settings[sName].handleSubmit, isDefault:true } );
			if(Fuji.Dialogs.Settings[sName].handleCancel)
				buttons.push( { text:Fuji.Messages.cancel, handler:Fuji.Dialogs.Settings[sName].handleCancel } );
			if(buttons.length > 0)
				Fuji.Dialogs.Instances[sName].cfg.queueProperty("buttons", buttons);
			Fuji.Dialogs.Instances[sName].setHeader(Fuji.Dialogs.Settings[sName].header);
			Fuji.Dialogs.Instances[sName].setBody(Fuji.Dialogs.Settings[sName].body);
			Fuji.Dialogs.Instances[sName].cfg.setProperty("icon", Fuji.Dialogs.Settings[sName].icon);
			if(Fuji.Dialogs.Settings[sName].onSuccess)
				Fuji.Dialogs.Instances[sName].cfg.queueProperty("onsuccess", Fuji.Dialogs.Settings[sName].onSuccess);
			Fuji.Dialogs.Instances[sName].render(document.body);
		}
		Fuji.Dialogs.Instances[sName].show();		
	},
	
	/**
	 * Creates a new delete group dialog (if it doesn't exist), and shows the dialog
	 * @param {String} sURI The uri of the group to be deleted
	 */	
	newDeleteGroupDialog : function(sURI) {
		Fuji.Dialogs.Settings.deleteDialog.uri = sURI;
		Fuji.Dialogs.newSimpleDialog('deleteGroupDialog');
	},
	
	/**
	 * Creates an error message dialog
	 * @param oResponse The XMLHTTPRequest response-object
	 */
	newErrorDialog : function(oResponse) {
		if(oResponse.status == '401')
		{
			Fuji.Dialogs.Settings.errorDialog401.uri = oResponse.argument.uri;
			Fuji.Dialogs.Settings.errorDialog401.loadBody();			
			Fuji.Dialogs.newSimpleDialog('errorDialog401');
		}
		else if(oResponse.status == '404')
		{
			Fuji.Dialogs.Settings.errorDialog404.uri = oResponse.argument.uri;
			Fuji.Dialogs.Settings.errorDialog404.loadBody();
			Fuji.Dialogs.newSimpleDialog('errorDialog404');
		}
		else
		{
			Fuji.Dialogs.Settings.errorDialogOther.uri = oResponse.argument.uri;
			Fuji.Dialogs.Settings.errorDialogOther.status = oResponse.status;
			Fuji.Dialogs.Settings.errorDialogOther.statusText = oResponse.statusText;
			Fuji.Dialogs.Settings.errorDialogOther.loadBody();		
			Fuji.Dialogs.newSimpleDialog('errorDialogOther');
		}
	},
	
	/**
	 * Creates a new edit item dialog (if it doesn't exist), and shows the dialog
	 * @param {String} sURI The uri of the item to be edit
	 */		
	newEditDialog : function(sURI) {
		if(!Fuji.Dialogs.Instances.createEditDialog || document.getElementById('createEditDialog_c').style.visibility == 'hidden')
		{
			var callback =
			{
				success: Fuji.updateEditDialog,
				failure: Fuji.newErrorDialog,
				argument: []
			}
			document.getElementById('createDialogHd').style.display = 'none';
			document.getElementById('editDialogHd').style.display = 'block';
			document.getElementById('createEditForm').action = sURI;
			document.getElementById('createEditForm').method = 'PUT';
			Fuji.Dialogs.Settings.createEditDialog.uri = sURI;
			YAHOO.util.Connect.asyncRequest('GET', sURI + '/source', callback, null);
			Fuji.Dialogs.newDialog('createEditDialog');
		}
	},
	
	/**
	 * Creates a new create item dialog (if it doesn't exist), and shows the dialog
	 * @param {String} sURI The uri of the item to add a new item to
	 * @param {Int} iType The type of the new item
	 */
	newCreateDialog : function(sURI, iType) {
		if(!Fuji.Dialogs.Instances.createEditDialog || document.getElementById('createEditDialog_c').style.visibility == 'hidden')
		{
			document.getElementById('editDialogHd').style.display = 'none';
			document.getElementById('createDialogHd').style.display = 'block';
			document.getElementById('createEditType').value = iType;
			document.getElementById('createEditForm').action = sURI;
			document.getElementById('createEditForm').method = 'POST';
			Fuji.Dialogs.Settings.createEditDialog.uri = sURI;
			Fuji.Dialogs.newDialog('createEditDialog');
		}
	},
	
	/**
	 * Creates a new delete item dialog (if it doesn't exist), and shows the dialog
	 * @param {String} sURI The uri of the item to be deleted
	 * @param {String} sParentURI The uri of the item's parent
	 */
	newDeleteDialog : function(sURI, sParentURI) {
		Fuji.Dialogs.Settings.deleteDialog.uri = sURI;
		Fuji.Dialogs.Settings.deleteDialog.parentURI = sParentURI;
		Fuji.Dialogs.newSimpleDialog('deleteDialog');
	}
}
Fuji.Dialogs.Instances = { /* All dialog instance objects created using newDialog are stored in here */ }
Fuji.Dialogs.Settings = { }
Fuji.Dialogs.Settings.serverSettingsDialog = {
	onSuccess : function() { location.reload(); },
	handleSubmit : function() { this.submit(); this.hide(); },
	handleCancel : function() { this.cancel(); this.hide(); }
}
Fuji.Dialogs.Settings.pluginsDialog = {
	onSuccess : function() { location.reload(); },
	handleSubmit : function() { this.submit(); this.hide(); },
	handleCancel : function() { this.cancel(); this.hide(); }
}
Fuji.Dialogs.Settings.prefDialog = {
	onSuccess : function() { location.reload(); },
	handleSubmit : function() {
		var oPrefPass1 = document.getElementById('prefPass1');
		var oPrefPass2 = document.getElementById('prefPass2');
		var oPrefEmail = document.getElementById('prefEmail');
		var oPrefPhoto = document.getElementById('prefPhoto');			
		if(oPrefPass1.value != oPrefPass2.value)
			alert(Fuji.Messages.passwords_do_not_match);
		else
		{
			if(oPrefEmail.value == '')
				alert(Fuji.Messages.email_empty);
			else
			{
				if(oPrefPhoto.value && oPrefPhoto.value.indexOf('http://') != 0)
					alert(Fuji.Messages.invalid_url);
				else
				{
					this.submit();
					this.hide();
				}
			}
		}
	},
	handleCancel : function() { this.cancel(); this.hide(); }
}
Fuji.Dialogs.Settings.newGroupDialog = {
	onSuccess : function() { Fuji.reloadItem("groups/"); },
	handleSubmit : function() { this.submit(); document.getElementById('newGroupF').reset(); this.hide(); },
	handleCancel : function() { this.cancel(); document.getElementById('newGroupF').reset(); this.hide(); }
}
Fuji.Dialogs.Settings.editGroupDialog = {
	onSuccess : function() { Fuji.reloadItem("groups/"); },
	handleSubmit : function() { this.submit(); document.getElementById('editGroupF').reset(); this.hide(); },
	handleCancel : function() { this.cancel(); document.getElementById('editGroupF').reset(); this.hide(); }
}
Fuji.Dialogs.Settings.configRightsDialog = {
	onSuccess : function() { },
	handleSubmit : function() { this.submit(); document.getElementById('configRightsF').reset(); this.hide(); },
	handleCancel : function() { this.cancel(); document.getElementById('configRightsF').reset(); this.hide(); }
}
Fuji.Dialogs.Settings.createEditDialog = {
	uri : null,
	onSuccess : function() { Fuji.reloadItem(Fuji.Dialogs.Settings.createEditDialog.uri, 999) },
	handleSubmit : function() {	if(Fuji.Editor.isEnabled)
					{
						document.getElementById('createEditContent').value = document.getElementById('editor').contentWindow.document.body.innerHTML;
						document.getElementById('createEditProcessor').value = 'xhtml';
					}
					else
						document.getElementById('createEditProcessor').value = 'bbcode';
					this.submit();
					document.getElementById('createEditForm').reset();
					Fuji.Util.removeChildNodes(document.getElementById('editor').contentWindow.document.body);
					this.hide(); },
	handleCancel : function() {	this.cancel();
					document.getElementById('createEditForm').reset();
					Fuji.Util.removeChildNodes(document.getElementById('editor').contentWindow.document.body);
					this.hide(); }
}
Fuji.Dialogs.Settings.deleteDialog = {
	uri : null,
	parentURI : null,	
	handleSubmit : function() { Fuji.httpDeleteAndReload(Fuji.Dialogs.Settings.deleteDialog.uri, Fuji.Dialogs.Settings.deleteDialog.parentURI); this.hide(); },
	handleCancel : function() { this.hide(); },
	header : Fuji.Messages.warning,
	body : Fuji.Messages.sure_delete_item,
	icon : YAHOO.widget.SimpleDialog.ICON_WARN
}
Fuji.Dialogs.Settings.deleteGroupDialog = {
	uri : null,
	handleSubmit : function() { Fuji.httpDeleteAndReload(Fuji.Dialogs.Settings.deleteDialog.uri, Fuji.Dialogs.Settings.deleteDialog.parentURI); this.hide(); },
	handleCancel : function() { this.hide(); },
	header : Fuji.Messages.warning,
	body : Fuji.Messages.sure_delete_group,
	icon : YAHOO.widget.SimpleDialog.ICON_WARN
}
Fuji.Dialogs.Settings.errorDialog401 = {
	handleSubmit : function() { this.hide(); },
	header : Fuji.Messages.error,
	loadBody : function() {	this.body = '<p>' + Fuji.Messages.error_loading_resource + ' ' + this.uri + '</p>' +
				'<p>' + Fuji.Messages.error_insufficient_rights + '</p>' +
				'<p>' + Fuji.Messages.possible_reasons +
				'<ul><li>' + Fuji.Messages.error_restricted_forum + '</li>' +
				'<li>' + Fuji.Messages.error_rights_revoked + '</li>' +
				'<li>' + Fuji.Messages.error_anonymous + '</li>' +
				'<li>' + Fuji.Messages.error_server_misconfiguration + '</li></ul></p>' },
	body : null,
	icon : YAHOO.widget.SimpleDialog.ICON_BLOCK
}
Fuji.Dialogs.Settings.errorDialog404 = {
	handleSubmit : function() { this.hide(); },
	header : Fuji.Messages.error,
	loadBody : function() {	this.body = '<p>' + Fuji.Messages.error_loading_resource + ' ' + this.uri + '.</p>' +
				'<p>' + Fuji.Messages.error_resource_cannot_be_found + '</p>' +
				'<p>' + Fuji.Messages.possible_reasons +
				'<ul><li>' + Fuji.Messages.error_deleted_resource + '</li>' +
				'<li>' + Fuji.Messages.error_invalid_url + '</li>' +
				'<li>' + Fuji.Messages.error_server_misconfiguration + '</li></ul></p>' },
	body : null,
	icon : YAHOO.widget.SimpleDialog.ICON_BLOCK,
	uri : null
}
Fuji.Dialogs.Settings.errorDialogOther = {
	handleSubmit : function() { this.hide(); },
	header : Fuji.Messages.error,
	loadBody : function() {	this.body = '<p>' + Fuji.Messages.error_loading_resource + '.</p>' +
				'<p>' + Fuji.Messages.error_unknown + ' ' + this.status + ' ' + this.statusText + '.</p>' +
				'<p>' + Fuji.Messages.possible_reasons +
				'<ul><li>' + Fuji.Messages.error_server_misconfiguration + '</li>' +
				'<li>' + Fuji.Messages.error_bug + '</li></ul></p>' +
				'<p>' + Fuji.Messages.contact_administrator + '</p>' },
	body : null,
	icon : YAHOO.widget.SimpleDialog.ICON_BLOCK,
	status : null,
	statusText : null
}
Fuji.Dialogs.Settings.loadingDialog = {
	header : Fuji.Messages.loading,
	body : Fuji.Messages.please_wait,
	icon : YAHOO.widget.SimpleDialog.ICON_INFO
}