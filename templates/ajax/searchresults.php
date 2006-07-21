<?php $page_size = 8; ?>
<?xml version="1.0" encoding="UTF-8"?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv"
	parent="item/1">
	<fuji:content>
		<div class="forumContainer box withpager">
		<h2 class="windowTitle"><?php echo L10n::$search ?></h2>
		<div class="threadsTableDiv content">
			<table class="threadsTable">
				<colgroup>
					<col width="40%" />
					<col />
					<col />
					<col />
					<col />
				</colgroup>
				<thead>
					<tr>
						<th><?php echo L10n::$topic ?></th>
						<th><?php echo L10n::$poster ?></th>
						<th><?php echo L10n::$replies ?></th>
						<th><?php echo L10n::$created ?></th>
						<th><?php echo L10n::$modified ?></th>
						<th><?php echo L10n::$score ?></th>
					</tr>
				</thead>
				<tbody>
				<?php 	$num_of_entries = count($this->entries);
					$i = 0;
					if($num_of_entries > 0)
					{
						for($n = ($this->page - 1) * $page_size; $n < min($num_of_entries, $this->page * $page_size); $n++) 
						{ 
							$entry = $this->entries[$n];
						?>
						<?php if ($entry->type == Entry::$TYPE_FORUM) { ?>
						<tr class="tableForumEntry <?php echo $i++ % 2 ? 'even' : 'odd' ?>">
							<td><a href="#<?php echo $entry->getURI() ?>"><?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></a></td>
							<td><a href="#<?php echo User::getUser($entry->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($entry->userref)->nickname, ENT_NOQUOTES) ?></a></td>
							<td><?php echo count($entry->getChildren()) ?></td>
							<td><?php echo $entry->getFormattedDate('created', L10n::$date_format) ?></td>
							<td><?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?></td>
							<td><?php echo ($entry->score * 100) . '%' ?></td>
						<?php } else if ($entry->type == Entry::$TYPE_POST) { ?>
						<tr class="tableThreadEntry <?php echo $i++ % 2 ? 'even' : 'odd' ?>">				
							<td><a href="#<?php echo $entry->getURI() ?>"><?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></a></td>
							<td><a href="#<?php echo User::getUser($entry->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($entry->userref)->nickname, ENT_NOQUOTES) ?></a></td>
							<td><?php echo count($entry->getChildren()) ?></td>
							<td><?php echo $entry->getFormattedDate('created', L10n::$date_format) ?></td>
							<td><?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?></td>
							<td><?php echo ($entry->score * 100) . '%' ?></td>
						<?php } else { ?>
						<tr class="tableUnsupportedEntry">				
							(<?php echo L10n::$type_unsupported ?>)
						<?php } ?>
						</tr>
						<?php } ?>
					<?php } else echo L10n::$error_search_no_results
					// TODO: Invalid XHTML here!
					?>
				</tbody>
			</table>
			</div>
		</div>
		<div class="threadPageSelector pager">
			<?php echo L10n::$page?>
			<?php if($this->page > 1) { ?><a href="#<?php /*echo $entry->getURI().'/'.($this->page-1)*/ ?>">&lt;</a> <?php } ?>
			<?php for($n = 1; $n <= max(0, $num_of_entries - 1) / $page_size + 1; $n++) { ?><a href="#<?php /*echo $entry->getURI().($n>1?'/'.$n:'')*/ ?>"><?php echo $n ?></a> <?php } ?>
			<?php if(max(0, $num_of_entries - 1) / $page_size > $this->page) { ?><a href="#<?php echo $entry->getURI().'/'.($this->page+1) ?>">&gt;</a> <?php } ?>
		</div>
		<div id="<?php /*echo $entry->getURI()*/ ?>_postsDiv" class="postsDiv"></div>
	</fuji:content>
</fuji:item>