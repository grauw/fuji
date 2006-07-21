<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2 class="windowTitle"><?php echo L10n::$search ?></h2>
			<div id="threadsTableDiv" class="content">
				<form name="searchF">
					<table>
						<tr><td>Query:</td><td><input id="searchQuery" type="text" name="query" /></td></tr>
						<tr>
							<td>Field:</td>
							<td>
								<select id="searchField" name="field">
									<option value="title" selected="Selected">Title</option>
									<option value="content">Content</option>
									<option value="userref">Poster</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Order:</td>
							<td>
								<select id="searchOrder" name="order">
									<option value="score" selected="Selected">Score</option>
									<option value="title">Title</option>
									<option value="created">Created</option>
									<option value="modified">Last modification</option>
									<option value="userref">Poster</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2">&#160;</td>
						</tr>
						<tr>
							<td colspan="2"><input type="button" value="Search" onclick="Fuji.searchAction()" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</fuji:content>
</fuji:item>