<?php
	require_once "register1.php";
	require_once "register2.php";
	require_once "../xhtml.php";
	if(isset($_POST['phase']))
		$current_phase = $_POST['phase'];
	else
		$current_phase = 0;
	$phases = array(	array("Welcome", "<p>Welcome to Fuji!</p><p>This wizard will help you set up your account.</p>"),
					 	array("Registration", new Register1()), 
						array("Finish", new Register2())
					);
	//echo XHTML::pageHeaders();
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xhtml_template->language ?>">
	<head>
		<title>Fuji User Registration</title>
		<link rel="stylesheet" href="style/default/css/default.css"/>
		<link rel="stylesheet" href="style/default/css/wizard.css"/>
	</head>
	<body>
		<div id="mainWindow" class="window">
		<form action="register" method="post">
		  <div class="windowTitle">Fuji User Registration</div>
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
		  	<?php } else { ?><input name="finish" type="button" value="Finish" onclick="location.assign('..')"></input>
		  	<?php } ?>
		  </div>
		  </form>
		</div>
	</body>
</html>
