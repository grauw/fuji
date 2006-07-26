<?php

require_once "processor.php";
/**
 * A simple BBCode-parser, based on the abstract Processor class.
 */
class BBCode extends Processor {

    public $ats= Array();
    public $tagsStack = Array();
    public function parse() {

        $input = func_get_arg(0);
        $match = false;

        $input = htmlspecialchars($input);

        $regexp = '/([^[]*?)(?:\[([\/?A-Za-z]+|\*)(?:=([^]?]+)|(\*)?)?\])|([^[]+)/';

        if($input) {
                   $output = preg_match_all($regexp, $input, $matches);

                   $match=true;
        }

        $texts    = $matches[1];
        $tags     = $matches[2];
        $tagextra = $matches[3];
        $trailer  = $matches[4];
        $rest	  = $matches[5];

        $result = "";
        $length = count($texts);

        $lock = false;
        for($i=0;$i<$length;$i++) {

            // check ook meteen de goede volgorde
            if (!$lock)
            {
              if($tags[$i] == "*")
              {
                 $pop = array_pop($this->tagsStack);
                 if ($pop != "*")
                    {
                      array_push($this->tagsStack, $pop);
                      $result.= "<li>";
                    }
                 else
                 {
                     $result.="$texts[$i]</li><li>";
                 }
                 array_push($this->tagsStack, $tags[$i]);

              }
              else if (substr($tags[$i],0,1) != "/")
              {
                 if ($tags[$i] == 'img')
                    $lock = true;
                 array_push($this->tagsStack, $tags[$i]);
                 $result .= $texts[$i].$this->convertTag($tags[$i], $tagextra[$i], $texts[$i+1]) . $trailer[$i];
              }
              else
              {
                 $done = false;
                 if ($tags[$i-1] != "img")
                        $result .= $texts[$i];
                 while ($done == false)
                 {
                   $pop = array_pop($this->tagsStack);
                   if ($tags[$i] != "/".$pop)
                   {
                      $result .= $this->convertTag("/".$pop, "", "");
                      $done = false;
                   }
                   else
                   {
                        $result .= $this->convertTag($tags[$i], $tagextra[$i], $texts[$i+1]) . $trailer[$i];
                        $done = true;
                   }
                   if (count($this->tagsStack)==0)
                      $done = true;
                 }
              }
            }
            else if ($tags[$i] == "/img")
            {
              array_pop($this->tagsStack);
              $lock = false;
            }

        }

	$result .= $rest[count($rest)-1];

                while (count($this->tagsStack) > 0)
        {
            $pop = array_pop($this->tagsStack);
            $result .= $this->convertTag("/".$pop,"","");
        }
        
        return  $result;
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
            "/quote" => "</div></div>",
            "/url" => "</a> ",
        );

        if(array_key_exists($tag, $tagsArray)) {
            return $tagsArray[$tag];
        }
        else if ($tag == "img") {
           if (substr($text,0,11) == "javascript:")
              return "<img src='' alt='' border='0'/>";
           else
             return "<img src='$text' alt='' border='0'/>";
        }
        else if ($tag == "url")
        {
           if ($tagextra == "")
           {   
               $text = trim($text);
               return "<a href='$text'>";
           }
           else
           {
           if (substr($tagextra,0,11) == "javascript:")
              return "<a href=''>";
           else
               return "<a href='$tagextra'>";
           }
        }
        else if($tag == "quote")
        {
           if($tagextra == "")
              return "<div class='quote'> Quote:<div class='quotecontent'>";
           else {
            if(substr($tagextra,0 ,6) == "&quot;" && substr($tagextra, (strlen($tagextra)-6),6) == "&quot;")  $tagextra = substr($tagextra, 6, strlen($tagextra)-12);

            return "<div class='quote'> $tagextra wrote:<div class='quotecontent'>";
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
                 return "</li></ol>";
               }
               else return "</li></ul>";
             }
        }
    }
}
