<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';

new Item_Source();
	
class Item_Source {
	
	function __construct()
	{
		ob_start();
		$this->httpGetSource();
		if($this->acceptsGZIP())
			$this->outputGZIP();
	}
	
	function httpGetSource()
	{
		header('Content-Type: application/xhtml+xml');
		$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
		$entry = Entry::getEntry($id);
		if ($entry && UserManager::getUser()->getRights($entry)->may_read) {
		?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
<title><?php echo $entry->title ?> source</title>
</head>
<body>
<?php 		$entry_data_keys = array_keys($entry->getData());
			$entry_data_values = array_values($entry->getData());
			for($n = 0; $n < count($entry_data_keys); $n++) echo "<span id=\"$entry_data_keys[$n]\">$entry_data_values[$n]</span>";
				echo "<span id=\"content\">" . $entry->getProcessedContent() . "</span>\n"; ?>
</body>
</html>
		<?php }
		else {
		}		
	}
	
	public function acceptsGZIP()
	{
		return strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') ? true : false;
	}
	
	public function outputGZIP()
	{
		$contents = ob_get_contents();
		ob_end_clean();
		header("Content-Encoding: gzip");
		$output = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		$output .= substr(gzcompress($contents), 0, -4);
		echo $output; 
		exit();	
	}	
} ?>