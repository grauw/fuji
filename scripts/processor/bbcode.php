<?php

require_once "processor.php";
/**
 * A simple BBCode-parser, based on the abstract Processor class.
 */
class BBCode extends Processor {

    public $ats= Array();
    public function parse() {

        $input = func_get_arg(0);
        $match = false;

        $input = htmlspecialchars($input);

        // $regexp = '/(.*?)(?:\[([\/?A-Za-z]+)(?:=([A-Za-z]+))?\])|(.+)/';
        // $regexp = '/(.*?)(?:\[([\/?A-Za-z]+)(?:=([a-zA-Z0-9:&%\/\#\$\-\_\.\+!\*\'\(\),]+)|(\*))?\])|(.+)/';

        //$regexp = '/((?:.|\r\n)*?)(?:\[([\/?A-Za-z]+|\*)(?:=([^]?]+)|(\*)?)?\])|((:?.|\r\n)+)/';
        $regexp = '/([^[]*?)(?:\[([\/?A-Za-z]+|\*)(?:=([^]?]+)|(\*)?)?\])|([^[]+)/';
        $regexp = '/([^[]*?)(?:\[([\/?A-Za-z]+|\*)(?:=([^]?]+)|(\*)?)?\])|([^[]+)/';


        //$regexp= "<([A-Z][A-Z0-9]*)[^>]*>.*?</\1>";

        if($input) {
                   $output = preg_match_all($regexp, $input, $matches);//, PREG_PATTERN_ORDER);// + PREG_OFFSET_CAPTURE);

                   $match=true;
        }


        print_r($matches);

        //$matches = $this->checkTagOrder($matches);

        $texts    = $matches[1];
        $tags     = $matches[2];
        $tagextra = $matches[3];
        $trailer  = $matches[4];

        $result = "";
        $length = count($texts);


        for($i=0;$i<$length;$i++) {

            // check ook meteen de goede volgorde

            $result .= $texts[$i] . $this->convertTag($tags[$i], $tagextra[$i]) . $trailer[$i];
        }

        print nl2br("\n\nresult: ".$result);


        // parse [b] strings
        //return preg_replace("[
        //           [tag]   tekst   [/tag]
    }

    function convertTag($tag) {
        $tag = func_get_arg(0);
        $tagextra = func_get_arg(1);
        
        $tagsArray = array(
            "b" => "<b>",
            "/b" => "</b>",
            "i" => "<i>",
            "/i" => "</i>",
            "u" => "<u>",
            "/u" => "</u>",
            "code" => "<div class=\"code\">",
            "/code" => "</div>",
            "*" => "</li><li>"
        );

        if(array_key_exists($tag, $tagsArray)) {
            return $tagsArray[$tag];
        }
        else
        {   
             if($tag == "list") {
               array_push($this->ats, $tagextra);
               if($tagextra == "1")
               {
                  return "<ol type='1'>";
               }
               else if($tagextra == "a")
               {
                 return "<ol type='a'>";
               }
               else return "<ul>";
             }
             else if ($tag == "/list")
             {
               $at = array_pop($this->ats);

               if($at == '1' || $at == 'a')
               {
                 return "</ol>";
               }
               else return "</ul>";
             }
        }
    }

    /*function checkTagOrder() {
        
        $matches = func_get_arg(0);
        
        $texts    = $matches[1];
        $tags     = $matches[2];
        $tagextra = $matches[3];
        $trailer  = $matches[4];

        $stack = array();

        for($i=0;$i<
        
    }*/
}
