<?php
require_once 'entry.php';

class Poll extends Entry {
    public $ns = 'http://fuji.sourceforge.net/ns';
    public $question;
    public $answer = array();
    public $votes = array();
    public $total = 0;
	
	static function loadPoll($entry) {
	    $poll = new Poll($entry->getData());
	    $poll->loadContent();
	    return $poll;
	}
	
	function createContent() {
	    $doc = new DOMDocument('1.0', 'utf-8');
	    $poll = $doc->createElementNS($this->ns, 'poll');
    	$poll->setAttributeNS($this->ns, 'votes', intval($this->total));
	    $doc->appendChild($poll);
	    $question = $doc->createElementNS($this->ns, 'question');
	    $question->appendChild($doc->createTextNode($this->question));
	    $poll->appendChild($question);
	    for($n = 1; $n <= count($this->answer); $n++)
	    {
    	    $answer = $doc->createElementNS($this->ns, 'answer');
    	    $answer->setAttributeNS($this->ns, 'number', $n);
    	    $answer->setAttributeNS($this->ns, 'votes', intval($this->votes[$n]));
    	    $answer->appendChild($doc->createTextNode($this->answer[$n]));
    	    $poll->appendChild($answer);
	    }    
        $this->content = $doc->saveXML();
	}
	
	function loadContent() {
	    $xmlcontent = DOMDocument::loadXML($this->content);
	    $poll = $xmlcontent->documentElement;
        $this->total = $poll->hasAttributeNS($this->ns, 'votes') ? $poll->getAttributeNS($this->ns, 'votes') : $poll->getAttribute('votes'); //buggy XML DOM
	    foreach($xmlcontent->getElementsByTagNameNS($this->ns, 'question') as $elm)
	        $this->question = $elm->textContent;
	    foreach($xmlcontent->getElementsByTagNameNS($this->ns, 'answer') as $elm)
	    {
	        $this->answer[$elm->hasAttributeNS($this->ns, 'number') ? $elm->getAttributeNS($this->ns, 'number') : $elm->getAttribute('number') ] = $elm->textContent;
	        $this->votes[$elm->hasAttributeNS($this->ns, 'votes') ? $elm->getAttributeNS($this->ns, 'votes') : $elm->getAttribute('votes') ] = $elm->textContent;
	    }
	}
	
	function addVote($number) {
	    $vote = new Entry();
        $vote->ref = $this->id;
    	$vote->type    = Entry::$TYPE_VOTE;
    	$vote->userref = UserManager::getUser()->id;
	    $doc = new DOMDocument('1.0', 'utf-8');
	    $poll = $doc->createElementNS($this->ns, 'vote');
    	$poll->setAttributeNS($this->ns, 'number', $number);
	    $doc->appendChild($poll);
        $vote->content = $doc->saveXML();
    	$vote->save();
        $this->recalcVotes();
        $this->save();
	}
	
	function hasVoted() {
		return count(Database::getArray("SELECT * FROM " . Database::$db_tprefix . "entries WHERE ref = '". $this->id ."' AND userref = '". UserManager::getUser()->id ."' AND type = '". Entry::$TYPE_VOTE ."' LIMIT 1")) > 0;
	}
	
	function recalcVotes() {
	    $total = 0;
	    $votes = array();
	    foreach($this->getChildren() as $child)
	    {
    	    $xmlcontent = DOMDocument::loadXML($child->content);
    	    $vote = $xmlcontent->documentElement;
            $votes[$vote->hasAttributeNS($this->ns, 'number') ? $vote->getAttributeNS($this->ns, 'number') : $vote->getAttribute('number')]++;
            $total++;
	    }
	    $this->total = $total;
	    $this->votes = $votes;
	    $this->createContent();
	}
}

?>
