<?php echo $this->getPrefix() ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->l10n->language ?>">
<head>
	<title><?php echo $this->entry->title ?></title>
	<link rel="alternate" type="application/atom+xml" title="Atom" href="/blog/atom.php" />
	<link rel="alternate" type="application/rss+xml" title="RSS" href="/blog/rss.php" />
	<link rel="meta" type="application/rdf+xml" title="FOAF" href="foaf.rdf" />
	<link rel="icon" type="image/png" href="/images/siteicon.png" />
	<?php echo $this->getHead(1) ?>
</head>
<body>
	<h1 class="logo"><a href="http://fuji.sourceforge.net">Fuji</a></h1>
	<div id="menuDiv" class="menuContainer box">
	<h2><?php echo $this->l10n->menu ?></h2>
	<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
		<p><?php echo $this->l10n->anonymous_user ?></p>
	<?php } else { ?>
		<p><?php echo $this->l10n->logged_in_as . ' ' . UserManager::getUser()->nickname ?></p>
	<?php } ?>		
	</div>	
	<div class="box">
	<h2><?php echo $this->l10n->fora ?></h2>
	
	<div class="content">
	<?php echo $this->entry->getProcessedContent() ?>

	<table>
		<colgroup>
			<col width="40%" />
			<col />
			<col />
			<col />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo $this->l10n->topic ?></th>
				<th><?php echo $this->l10n->poster ?></th>
				<th><?php echo $this->l10n->replies ?></th>
				<th><?php echo $this->l10n->created ?></th>
				<th><?php echo $this->l10n->modified ?></th>
			</tr>
		</thead>
		<tbody>
		<?php 	$num_of_children = count($this->entry->getChildren());
				$i = 0;
				if($num_of_children > 0)
				foreach($this->entry->getChildren() as $post) { ?>
				<tr class="<?php echo $i++ % 2 ? 'even' : 'odd' ?>">
					<td><a href="../<?php echo $post->getURI() ?>"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a></td>
					<td><a href="../<?php echo User::getUser($post->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></td>
					<td><?php echo count($post->getChildren()) ?></td>
					<td><?php echo $post->getFormattedDate('created', $this->l10n->datetime_format) ?></td>
					<td><?php echo $post->getFormattedDate('modified', $this->l10n->datetime_format) ?></td>
				</tr>
		<?php } else echo $this->l10n->error_no_threads
			// TODO: Invalid XHTML here!
			?>
		</tbody>
	</table>
	</div>
	</div>
</body>
</html>