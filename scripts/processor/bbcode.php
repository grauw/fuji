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

            if ($tags[$i-1] != "img")
                $result .= $texts[$i] . $this->convertTag($tags[$i], $tagextra[$i], $texts[$i+1]) . $trailer[$i];
        }

        print nl2br("\n\nresult: ".$result);


        // parse [b] strings
        //return preg_replace("[
        //           [tag]   tekst   [/tag]
    }

    function convertTag() {
        $tag = func_get_arg(0);
        $tagextra = func_get_arg(1);
        $text = func_get_arg(2);

        $tagsArray = array(
            "b" => "<b>",
            "/b" => "</b>",
            "i" => "<i>",
            "/i" => "</i>",
            "u" => "<u>",
            "/u" => "</u>",
            "code" => "<div class=\"code\">",
            "/code" => "</div>",
            "/quote" => "</div>",
            "/url" => "</a>",
            "/img" => "</img>",
            "*" => "</li><li>"
        );

        if(array_key_exists($tag, $tagsArray)) {
            return $tagsArray[$tag];
        }
        else if ($tag == "img") {
             return "<img src='$text' alt='' border='0'>";
        }
        else if ($tag == "url")
        {
           if ($tagextra == "")
           {   
               $text = trim($text);
               return "<a href='$text'>";
           }
           else
           {    return "<a href='$tagextra'>";
           }
        }
        else if($tag == "quote")
        {
           if($tagextra == "")
              return "<div class='quoter'> Quote:</div><div class='quote'>";
           else {
            if(substr($tagextra,0 ,6) == "&quot;" && substr($tagextra, (strlen($tagextra)-6),6) == "&quot;")  $tagextra = substr($tagextra, 6, strlen($tagextra)-12);

            return "<div class='quoter'> $tagextra wrote:</div><div class='quote'>";
            }
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
