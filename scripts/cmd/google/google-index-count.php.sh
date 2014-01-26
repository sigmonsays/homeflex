#!/bin/env php
<?
	printf("Getting page count...");
	flush();

	@$data = join("", file("http://www.google.com/search?hl=en&ie=UTF-8&oe=UTF-8&q=the&btnG=Google+Search"));

	if (!$data) die("Unable to get data\n");

	$snafu = split("<font size=-1 color=#ffffff>", $data);

	$butter = $snafu[3];

	$toast = strip_tags($butter);

	preg_match("/Results 1 - 10 of about ([0-9,]+)\./", $toast, $peanut);

	list(, $total) = $peanut;

	printf(" done ($total results)\n");
?>
