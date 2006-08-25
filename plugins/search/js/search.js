Fuji.Search = {
	searchAction : function() {
		var query = document.getElementById('searchQuery').value;
		var field = document.getElementById('searchField').value;
		var order = document.getElementById('searchOrder').value;
		location.hash = 'plugins/search' + '?field=' + field + '&query=' + query + '&order=' + order;
	}
}