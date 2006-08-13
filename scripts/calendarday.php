<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Calendar extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$template = Template::getTemplate();
		$template->today = time();
		if (isset($_GET['date']))
			$template->date = strtotime($_GET['date']);
		else
			$template->date = $template->today;
		$template->entries = self::threadsFromEntries(Entry::getEntriesByDate(date('Y-m-d', $template->date),
			date('Y-m-d', strtotime('next day', $template->date))));
		$template->applyView('calendarday');
	}
	
	static function threadsFromEntries($entries) {
		$threads = array();
		foreach ($entries as $entry) {
			$thread = self::threadFromEntry($entry);
			$threads['a'.$thread->id] = $thread;
		}
		return $threads;
	}
	
	static function threadFromEntry($entry) {
		if ($entry->getParent()->type == 3) {
			return $entry;
		} else {
			return self::threadFromEntry($entry->getParent());
		}
	}
	
}
	
new Calendar();
	
?>