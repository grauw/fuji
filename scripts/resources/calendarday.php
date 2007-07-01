<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class CalendarDay extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$template = Template::getTemplate();
		$template->today = time();
		$uriparts = $this->request->getRequestURIParts();
		if (isset($uriparts[1]))
			$template->date = strtotime($uriparts[1]);
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
			if ($thread)
				$threads['a'.$thread->id] = $thread;
		}
		return $threads;
	}
	
	static function threadFromEntry($entry) {
		$parent = $entry->getParent();
		if ($parent) {
			if ($parent->type == 3) {
				return $entry;
			} else {
				return self::threadFromEntry($parent);
			}
		} else {
			return NULL;
		}
	}
	
}

?>