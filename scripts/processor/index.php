<?php

require_once "bbcode.php";

/*
Let op: Dit bestand is bedoeld om te testen en hoort niet in de uiteindelijke release thuis.
*/
$bbcode = new BBCode();

$string = 'hallo [url=http://www.cs.uu.nl/blaefv123235(($$#(&%(&%&&$&)(&%.jpg]ik[/url] ben Roland en [b][i]maak een fout[/b][/i]. En nog een zin die nergens over [*]gaat.';

print "<pre>";
print_r($bbcode->parse($string));
print "</pre>";
?>
