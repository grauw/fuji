<?php

require_once "bbcode.php";

/*
Let op: Dit bestand is bedoeld om te testen en hoort niet in de uiteindelijke release thuis.
*/
$bbcode = new BBCode();

/*$string = '[list]
[*] 
groot item 1 en 35245
[*] item 2
[/list]';*/

$string = '--- Begin ---
Dit is [b] [i] hallo [/b] [/i]  [quote="Reyn"] Hallo [/quote] [url=http://lfl]een link[/url] [url]www.google.nl[/url] een erg [a] [b] [i] leuke   test [/i][/b] [/a] fwe [b] fjieowf[/b]'
       .
'[img] http://msnbcmedia.msn.com/j/msnbc/Components/Video/Webcasts/entertainmentbuzz.vsmall.jpg [/img]
[url=http://www.msn.com][img] http://msnbcmedia.msn.com/j/msnbc/Components/Video/Webcasts/entertainmentbuzz.vsmall.jpg [/img][/url]


[list][*]Rood
[*]Blauw
[*]Geel
[/list]
[list=1][*]Rood
[*]Blauw
[*]Geel
[/list]
[list=a][*]Rood
[*]Blauw
[*]Geel
[/list]
[i]


Hallo 2
[/i] fwefe
fewfwef
--- Einde ---
';

print "<pre>";
print($bbcode->parse($string));
print "</pre>";
?>
