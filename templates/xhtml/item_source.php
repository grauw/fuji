<?php echo $this->getPrefix(); ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title><?php echo $this->entry->title ?> source</title>
	<?php echo $this->getHead() ?>
</head>
<body>
<?php 		$entry_data_keys = array_keys($this->entry->getData());
			$entry_data_values = array_values($this->entry->getData());
			for($n = 0; $n < count($entry_data_keys); $n++) echo "<span id=\"$entry_data_keys[$n]\">$entry_data_values[$n]</span>";
				echo "<span id=\"content\">" . $this->entry->getContent() . "</span>\n"; ?>
</body>
</html>