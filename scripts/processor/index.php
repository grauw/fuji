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
Dit is [b] [i] hallo [/b] [/i]  [quote="Reyn"] Hallo [/quote] [url=http://lfl] een link [/url] een erg [a] [b] [i] leuke   test [/i][/b] [/a] fwe [b] fjieowf[/b]'
       .
'[list][*]Rood
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
print_r($bbcode->parse($string));
print "</pre>";
?>
