<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class CalendarDay extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$this->template->today = time();
		$uriparts = $this->request->getRequestURIParts();
		if (isset($uriparts[1]))
			$this->template->date = strtotime($uriparts[1]);
		else
			$this->template->date = $this->template->today;
		$this->template->entries = self::threadsFromEntries(Entry::getEntriesByDate(date('Y-m-d', $this->template->date),
			date('Y-m-d', strtotime('next day', $this->template->date))));
		$this->template->applyView('calendarday');
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