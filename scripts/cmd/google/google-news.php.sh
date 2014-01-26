#!/bin/env php
<?
function htmldecode($encoded) {
   return strtr($encoded,array_flip(get_html_translation_table(HTML_ENTITIES)));
}

list(, $url) = $_SERVER['argv'];

//	$url = "http://news.google.com/news/gntechnologyleftnav.html";

if (empty($url)) die;

	$data = join("", file($url));

	$foo = strip_tags($data, "<font><br><a>");
	$jew = split("<a", $foo);
	$c = count($jew);
	for($i=0; $i<$c; $i++) {
		if (strpos($jew[$i], "class=y")) {

			$dad = str_replace("&nbsp;", " ", $jew[$i]);

			$poo = split("\n", $dad);

			preg_match("/href=\"(.+)\"\>(.+)\<\/a\>/", $poo[0], $matches);
			list(, $url, $title) = $matches;

			list(, $url) = explode("&q=", $url);
			$url = urldecode($url);

			list(, $source, $desc) = explode("<br>", $poo[0]);

			$desc = htmldecode(strip_tags($desc));

			$source = htmldecode(strip_tags($source));

			/* print out stuff */
			echo "<A HREF=\"$url\"><FONT SIZE=+1>$title</FONT></A><BR>\n";
			echo "<B>Source:</B> $source<BR>$desc<BR><BR>\n";
		}
	}


?>
