<?php echo $this->getPrefix() ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->l10n->language ?>">
<head>
	<title><?php echo $this->l10n->login ?></title>
	<?php echo $this->getHead() ?>
</head>
<body>
	<h1 class="logo"><a href="<?php echo $this->l10n->forum_link ?>"><?php echo $this->l10n->forum_name ?></a></h1>
	<div id="menuDiv" class="menuContainer box">
	<h2><?php echo $this->l10n->menu ?></h2>
	<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
		<p><?php echo $this->l10n->anonymous_user ?></p>
	<?php } else { ?>
		<p><?php echo $this->l10n->logged_in_as . ' ' . UserManager::getUser()->nickname ?></p>
	<?php } ?>
	</div>	
	<div class="box">
	<h2><?php echo $this->l10n->login ?></h2>
	
	<div class="content">

	<form action="session-login" method="POST">
		
		<p><?php echo $this->l10n->enter_login_details ?></p>
		
		<table>
			<tbody>
				<tr>
					<th><label for="username"><?php echo $this->l10n->nickname ?></label></th>
					<td><input type="text" name="username" id="username" /></td>
				</tr>
				<tr>
					<th><label for="password"><?php echo $this->l10n->password ?></label></th>
					<td><input type="password" name="password" id="password" /></td>
				</tr>
			</tbody>
		</table>
		
		<input type="submit" value="<?php echo $this->l10n->login ?>" />
	</form>

	</div>
	</div>
</body>
</html>