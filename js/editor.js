Fuji.Editor = {
	characterStyles: new Array("abbr", "acronym", "cite", "del", "dfn", "ins", "kbd", "em", "strong", "code", "var", "samp"),
	init: function()
	{
		if(document.getElementById('editor').contentWindow.document.designMode /* TODO: Test if designMode is supported */ )
		{
			document.getElementById('createF').style.display = 'none';
			document.getElementById('editorContainer').style.display = 'block';
			document.getElementById('editor').contentWindow.document.designMode="on";
			document.getElementById('editor').contentWindow.document.execCommand('formatblock', false, 'p');
		}
	},
	disable: function()
	{
		if(document.getElementById('editor').contentWindow.document.designMode /* TODO: Test if designMode is supported */ )
		{
			document.getElementById('editorContainer').style.display = 'none';
			document.getElementById('createF').style.display = 'block';
			document.getElementById('editor').contentWindow.document.designMode="off";
		}		
	},
	clickAction: function(sName)
	{
		oEditor = document.getElementById('editor');
		if(sName == 'createlink')
		{
			var sURL = prompt(Fuji.Messages.enter_link_url, "http://");
			if (sURL != null && sURL != "")
				oEditor.contentWindow.document.execCommand("createlink", false, sURL);
		}
		if(sName == 'insertimage')
		{
			var sURL = prompt(Fuji.Messages.enter_image_url, "http://");
			if (sURL != null && sURL != "")
				oEditor.contentWindow.document.execCommand("insertimage", false, sURL);
		}
		if(sName == 'createtable')
		{
			var size = prompt(Fuji.Messages.enter_table_size, "3x3");
			if (size != null && size != "")
			{
				sizes = size.split('x');
				sizeC = parseInt(sizes[0]);
				sizeR = parseInt(sizes[1]);
				if(!isNaN(sizeC) && !isNaN(sizeR) && isFinite(sizeC) && isFinite(sizeR) && sizeC > 0 && sizeR > 1)
				{
					oNode = oEditor.contentWindow.document.createElement('table');
					oTheadNode = oEditor.contentWindow.document.createElement('thead');
					oTbodyNode = oEditor.contentWindow.document.createElement('tbody');
					oTheadTrNode = oEditor.contentWindow.document.createElement('tr');
					for(c = 0; c < sizeC; c++)
					{
						oTdNode = oEditor.contentWindow.document.createElement('td');
						oTdNode.appendChild(oEditor.contentWindow.document.createTextNode(' '));
						oTheadTrNode.appendChild(oTdNode);
					}
					oTheadNode.appendChild(oTheadTrNode);
					oNode.appendChild(oTheadNode);
					for(r = 1; r < sizeR; r++)
					{
						oTbodyTrNode = oEditor.contentWindow.document.createElement('tr');
						for(c = 0; c < sizeC; c++)
						{
							oTdNode = oEditor.contentWindow.document.createElement('td');
							oTdNode.appendChild(oEditor.contentWindow.document.createTextNode(' '));
							oTbodyTrNode.appendChild(oTdNode);
						}
						oTbodyNode.appendChild(oTbodyTrNode);
					}
					oNode.appendChild(oTbodyNode);
					oSelection = oEditor.contentWindow.getSelection();
					oRange = oSelection.getRangeAt(0);
					oSelection.removeAllRanges();
					if(!oRange.collapsed)
					{
						oRange.deleteContents();
					}
					oContainer = oRange.startContainer;
					iPos = oRange.startOffset;
					oParent = oContainer.parentNode;
					sText = oContainer.nodeValue;
					if(sText)
					{
						sTextBefore = sText.substr(0, iPos);
						sTextAfter = sText.substr(iPos);
						oTextBefore = oEditor.contentWindow.document.createTextNode(sTextBefore);
						oTextAfter = oEditor.contentWindow.document.createTextNode(sTextAfter);
						oParent.insertBefore(oTextAfter, oContainer);
						oParent.insertBefore(oNode, oTextAfter);
						oParent.insertBefore(oTextBefore, oNode);
					}
					else
						oParent.appendChild(oNode);
					oParent.removeChild(oContainer);
					oRange = document.createRange();
					oRange.setEnd(oNode, 0);
					oRange.setStart(oNode, 0);
				}
			}
		}
		if(sName == 'insertol')
		{
			oEditor.contentWindow.document.execCommand('insertorderedlist', false, null);
			oEditor.contentWindow.focus();
		}
		if(sName == 'insertul')
		{
			oEditor.contentWindow.document.execCommand('insertunorderedlist', false, null);
			oEditor.contentWindow.focus();
		}
	},
	changeAction: function(oElm)
	{
		sName = oElm.options[0].value;
		sValue = oElm.options[oElm.selectedIndex].value;
		oEditor = document.getElementById('editor');
		if(oElm.selectedIndex != 0)
		{
			if(sName == 'paragraph style')
			{
				oEditor.contentWindow.document.execCommand('formatblock', false, sValue);
			}
			if(sName == 'character style')
			{
				Fuji.Editor.removeStyle(oEditor.contentWindow);
				if(sValue != 'default')
				{
					oNode = oEditor.contentWindow.document.createElement(sValue);
					Fuji.Editor.addStyle(oEditor.contentWindow, oNode);
				}
			}
			oEditor.contentWindow.focus();
		}
		oElm.selectedIndex = 0;
	},
	addStyle: function(oWindow, oNode)
	{
		oSelection = oWindow.getSelection();
		oRange = oSelection.getRangeAt(0);
		oSelection.removeAllRanges();
		if(oRange.collapsed)
		{
			oTextNode = oWindow.document.createTextNode('');
			oNode.appendChild(oTextNode);
			oContainer = oRange.startContainer;
			iPos = oRange.startOffset;
			oParent = oContainer.parentNode;
			sText = oContainer.nodeValue;
			if(sText)
			{
				sTextBefore = sText.substr(0, iPos);
				sTextAfter = sText.substr(iPos);
				oTextBefore = oWindow.document.createTextNode(sTextBefore);
				oTextAfter = oWindow.document.createTextNode(sTextAfter);
				oParent.insertBefore(oTextAfter, oContainer);
				oParent.insertBefore(oNode, oTextAfter);
				oParent.insertBefore(oTextBefore, oNode);
			}
			else
				oRange.insertNode(oNode);
			oParent.removeChild(oContainer);
			oRange = document.createRange();
			oRange.setEnd(oTextNode, 0);
			oRange.setStart(oTextNode, 0);
		}
		else
		{
			oRange.surroundContents(oNode);
		}
		oSelection.addRange(oRange);
	},
	removeStyle: function(oWindow)
	{
		oSelection = oWindow.getSelection();
		oRange = oSelection.getRangeAt(0);
		oSelection.removeAllRanges();
		oStartContainer = oRange.startContainer;
		oEndContainer = oRange.endContainer;
		iStartOffset = oRange.startOffset;
		iEndOffset = oRange.endOffset;
		oBeforeNode = null;
		oAfterNode = null;
		if(oStartContainer.nodeType == Fuji.Util.TEXT_NODE && iStartOffset != 0
			&& Fuji.Util.arraySearch(Fuji.Editor.characterStyles, oStartContainer.parentNode.nodeName.toLowerCase()) !== false)
		{
			newRange = oWindow.document.createRange();
			newRange.setStartBefore(oStartContainer.parentNode);
			newRange.setEnd(oStartContainer, iStartOffset);
			oBeforeNode = newRange.extractContents();
		}
		if(oEndContainer.nodeType == Fuji.Util.TEXT_NODE && iEndOffset != oEndContainer.length
			&& Fuji.Util.arraySearch(Fuji.Editor.characterStyles, oEndContainer.parentNode.nodeName.toLowerCase()) !== false)
		{
			newRange = oWindow.document.createRange();
			newRange.setStart(oEndContainer, iEndOffset);
			newRange.setEndAfter(oEndContainer.parentNode);
			oAfterNode = newRange.extractContents();
		}		
		sTextContent = oRange.extractContents().textContent;
		if(sTextContent != "")
		{
			oTextNode = oWindow.document.createTextNode(sTextContent);
			if(oBeforeNode)
				oRange.setStartBefore(oStartContainer.parentNode);
			if(oAfterNode)
				oRange.setEndAfter(oEndContainer.parentNode);
			oRange.deleteContents();
			if(oAfterNode)
				oRange.insertNode(oAfterNode);
			oRange.insertNode(oTextNode);
			if(oBeforeNode)
				oRange.insertNode(oBeforeNode);
			oRange.selectNode(oTextNode);
		}
		oSelection.addRange(oRange);
	}
} 
