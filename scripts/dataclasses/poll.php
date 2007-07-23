<?php
require_once 'entry.php';

class Poll extends Entry {
    public $ns = 'http://fuji.sourceforge.net/ns';
    public $question;
    public $answer = array();
	
	function createContent() {
	    $xmldoc = new DOMDocument('1.0', 'utf-8');
	    $xmlpoll = $xmldoc->createElementNS($this->ns, 'poll');
	    $xmldoc->appendChild($xmlpoll);
	    $question = $xmldoc->createElementNS($this->ns, 'question');
	    $question->appendChild($xmldoc->createTextNode($this->question));
	    $xmlpoll->appendChild($question);
	    for($n = 1; $n <= count($this->answer); $n++)
	    {
    	    $answer = $xmldoc->createElementNS($this->ns, 'answer');
    	    $answer->setAttributeNS($this->ns, 'answernumber', $n);
    	    $answer->appendChild($xmldoc->createTextNode($this->answer[$n]));
    	    $xmlpoll->appendChild($answer);
	    }    
        $this->content = $xmldoc->saveXML();
	}
	
	function loadContent() {
	    $xmlcontent = DOMDocument::loadXML($this->content);
	    foreach($xmlcontent->getElementsByTagNameNS($this->ns, 'question') as $elm)
	        $this->question = $elm->textContent;
	    foreach($xmlcontent->getElementsByTagNameNS($this->ns, 'answer') as $elm)
	        $this->answer[$elm->hasAttributeNS($this->ns, 'answernumber') ? $elm->getAttributeNS($this->ns, 'answernumber') : $elm->getAttribute('answernumber')   ] = $elm->textContent; //buggy XML DOM
	}
}

?>
