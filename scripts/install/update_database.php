<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xhtml_template->language ?>">
	<head>
		<title>Fuji Installer</title>
	</head>
		<h1>Updates the database tables</h1>
<?php
set_include_path('../..');
require_once 'database.php';
new InstallDb();
?>
	</body>
</html>
