<?php
	require_once "install1.php";
	require_once "install2.php";
	if(isset($_POST['phase']))
		$current_phase = $_POST['phase'];
	else
		$current_phase = 0;
	$phases = array(	array("Welcome", "<p>Welcome to the Fuji Installer!</p><p>This Installer will guide you through the steps required to setup your Fuji Forum.</p>"),
					 	array("Database setup", new Install1()), 
						array("Administrative account", new Install2()),
						array("Finish", "<p>You have successfully completed the installer, and your Fuji Forum is ready to use!</p>")
					);
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xhtml_template->language ?>">
	<head>
		<title>Fuji Installer</title>
		<link rel="stylesheet" href="style/default/css/default.css" type="text/css" />
		<link rel="stylesheet" href="style/default/css/install.css" type="text/css" />
	</head>
	<body>
<?php if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') { ?>	
		<div id="mainWindow" class="window">
		<form action="install" method="post">
		  <div class="windowTitle">Fuji Installer</div>
		  <div class="windowSide">
		  	<div class="logo">Fuji</div>
		  	<div class="windowSideContent">
		  		<ul>
		  			<?php	for($n = 0; $n < count($phases); $n++)
		  					{
		  						echo '<li' . ($n == $current_phase? ' class="activeItem"' : '') . '>' . $phases[$n][0] . "</li>\n";
		  					}
		  			?>
		  		</ul>
		  	</div>
		  </div>		  
		  <div class="windowContent">
		  	<?php if(is_string($phases[$current_phase][1]))
		  			echo $phases[$current_phase][1];
		  		  else
		  		  	$phases[$current_phase][1]->execute(); ?>
		  </div>
		  <div class="buttonBar">
		  	<input id="phase" name="phase" type="hidden" value="<?php echo $current_phase ?>"></input> 
		  	<?php if($current_phase > 0) { ?><input name="previous" type="submit" value="Previous" onclick="document.getElementById('phase').value = <?php echo $current_phase - 1 ?>"></input>
		  	<?php } if($current_phase < count($phases) - 1) { ?><input name="next" type="submit" value="Next" onclick="document.getElementById('phase').value = <?php if(isset($target_phase)) echo $target_phase; else echo $current_phase + 1 ?>"></input>
		  	<?php } else { ?><input name="finish" type="button" value="Finish" onclick="location.assign('.')"></input>
		  	<?php } ?>
		  </div>
		  </form>
		</div>
<?php } else echo 'The Fuji Installer must be run from localhost.'?>		
	</body>
</html>
