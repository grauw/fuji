<?php echo $this->getPrefix() ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->l10n->language ?>">
<head>
	<title><?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></title>
	<?php echo $this->getHead() ?>
</head>
<body>
	<h1 class="logo"><a href="<?php echo $this->l10n->forum_link ?>"><?php echo $this->l10n->forum_name ?></a></h1>
	<div id="menuDiv" class="menuContainer box">
	<h2><?php echo $this->l10n->menu ?></h2>
	<p>
		<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
			<?php echo $this->l10n->anonymous_user ?>
		<?php } else { ?>
			<?php echo $this->l10n->logged_in_as . ' ' . UserManager::getUser()->nickname ?>
		<?php } ?>
		<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
			(<a href="<?php echo HttpRequest::$request->getContextPath() ?>/session-login"><?php echo $this->l10n->login ?></a>)
		<?php } else { ?>
			(<a href="<?php echo HttpRequest::$request->getContextPath() ?>/session-logout"><?php echo $this->l10n->logout ?></a>)
		<?php } ?>
	</p>
	<p><?php echo $this->l10n->location ?>: <?php echo $this->getBreadcrumb() ?></p>
	</div>
	<div class="forumContainer box">
	<h2><?php echo $this->l10n->threads ?> – <?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></h2>
	<div class="content">
	
	<div class="forumcontent"><?php echo $this->entry->getProcessedContent() ?></div>
	
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
				<?php if ($post->type == Entry::$TYPE_FORUM) { ?>
				<tr class="tableForumEntry <?php echo $i++ % 2 ? 'even' : 'odd' ?>">
					<td><a href="<?php echo $post->getRootedURI() ?>"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a></td>
					<td><a href="<?php echo User::getUser($post->userref)->getRootedURI() ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></td>
					<td><?php echo count($post->getChildren()) ?></td>
					<td><?php echo $post->getFormattedDate('created', $this->l10n->datetime_format) ?></td>
					<td><?php echo $post->getFormattedDate('modified', $this->l10n->datetime_format) ?></td>
				
				<?php } else if ($post->type == Entry::$TYPE_POST) { ?>
				<tr class="tableThreadEntry <?php echo $i++ % 2 ? 'even' : 'odd' ?>">				
					<td><a href="<?php echo $post->getRootedURI() ?>"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a></td>
					<td><a href="<?php echo User::getUser($post->userref)->getRootedURI() ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></td>
					<td><?php echo count($post->getChildren()) ?></td>
					<td><?php echo $post->getFormattedDate('created', $this->l10n->datetime_format) ?></td>
					<td><?php echo $post->getFormattedDate('modified', $this->l10n->datetime_format) ?></td>
				<?php } else { ?>
				<tr class="tableUnsupportedEntry">				
					(<?php echo $this->l10n->type_unsupported ?>)
				
				<?php } ?>
			</tr>
		<?php } else echo $this->l10n->error_no_threads
			// TODO: Invalid XHTML here!
			?>
		</tbody>
	</table>
	</div>
			
	<?php if (UserManager::getUser()->getRights($this->entry)->may_post) { ?>
	<div class="box postreply">
		<!-- TODO: put style in CSS -->
		<h3><?php echo $this->l10n->new ?></h3>
		<form action="<?php echo $this->entry->getRootedURI() ?>" method="POST" class="content">
			<input type="hidden" name="type" value="<?php echo $this->entry->type == Entry::$TYPE_FORUM ? Entry::$TYPE_POST : Entry::$TYPE_FORUM ?>" />
			<input type="hidden" name="processor" value="xhtml" />
			<label><?php echo $this->l10n->title ?>:
				<input name="title" style="width:30em;" />
			</label><br />
			<label><?php echo $this->l10n->message ?>:<br />
				<textarea name="content" style="width:40em;"></textarea>
			</label><br />
			<input type="submit" value="<?php echo $this->l10n->submit ?>" />
		</form>
	</div>
	<?php } ?>
	</div>
</body>
</html>