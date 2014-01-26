#!/usr/bin/env php
<?
	require("/etc/homeflex.php");

	require("$localPath/class/dbi.class.php");
	require("$localPath/class/dbi_mysql.class.php");

	require("$localPath/inc/functions.php");
	require("$localPath/inc/db.php");


	require("$localPath/random/scripts/functions.php");

	srand(microtime() * 1000000);

	// random number of the day
	$x = rand(420, 9999999999);
	write_data("random-number", $x);

	// random image
	$img = join("", file(SERVER_URL . "/homeflex/random/scripts/random-graph.php"));
	write_data("random-graph", $img, "png");

	// random word
	list($count) = file("$localPath/random/data/words.count");
	$wordx = rand(1, intval($count));
	$words = file("$localPath/random/data/words");
	$word_of_day =  $words[$wordx];
	write_data("random-word", $words[$wordx]);

	// random definition
	write_data("random-definition", word_definition( $words[$wordx] ) );

	// random mime type
	$qry = "SELECT * FROM {$dbTables['mimetypes']} ORDER BY RAND() LIMIT 1";
	$sql = $dbi->db->query($qry);
	if ($sql && $dbi->db->num_rows($sql)) {
		$obj = $dbi->db->fetch_object($sql);
		write_data('random-mime', $obj->type . ":" . $obj->extension);
	}

	// random project
	$qry = "SELECT id,name FROM {$dbTables['projects']} ORDER BY RAND() LIMIT 1";
	$sql = $dbi->db->query($qry);
	if ($sql && $dbi->db->num_rows($sql)) {
		$obj = $dbi->db->fetch_object($sql);
		write_data('random-project', $obj->id . ":" . $obj->name);
	}

	// random port of the day
	$qry = "SELECT id FROM {$dbTables['services']} ORDER BY RAND() LIMIT 1";
	$sql = $dbi->db->query($qry);
	if ($sql && $dbi->db->num_rows($sql)) {
		$obj = $dbi->db->fetch_object($sql);
		write_data('random-port', $obj->id);
	}

	// random command of the day
	$qry = "SELECT day FROM {$dbTables['cotd']} ORDER BY RAND() LIMIT 1";
	$sql = $dbi->db->query($qry);
	if ($sql && $dbi->db->num_rows($sql)) {
		$obj = $dbi->db->fetch_object($sql);
		write_data('random-command', $obj->day);
	}

	// random movie
	$qry = "SELECT id FROM {$dbTables['movies']} ORDER BY RAND() LIMIT 1";
	$sql = $dbi->db->query($qry);
	if ($sql && $dbi->db->num_rows($sql)) {
		$obj = $dbi->db->fetch_object($sql);
		write_data('random-movie', $obj->id);
	}

	// random skin of the day
	$qry = "SELECT id,name FROM {$dbTables['skins']} WHERE active='1' ORDER BY RAND() LIMIT 1";
	$sql = $dbi->db->query($qry);
	if ($sql && $dbi->db->num_rows($sql)) {
		$obj = $dbi->db->fetch_object($sql);
		write_data('random-skin', $obj->id . ":" . $obj->name);
	}

	// random process of the day
	$list = `ps waux`;

	$shit = split("\n", $list);
	$c = count($shit);
	$x = rand(0, $c);
	write_data('random-process', $shit[$x] );

	// random color
	$color = "#";
	for($i=0; $i<3; $i++) {
		$color .= dechex(rand(0, 255));
	}
	write_data('random-color', $color);


?>
