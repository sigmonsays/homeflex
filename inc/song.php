<?
	define("SONG_FILE", "/tmp/current-song.txt");

	$mtime = filemtime(SONG_FILE);

	$time = time() - 300;

/*
	echo "mtime: " . date("r", $mtime) . "<br>\n";
	echo "time: " . date("r", $time) . "<br>\n";

*/

	echo "Currently listening to <b>";

	if ($time < $mtime) {
		list($song) = file(SONG_FILE);
		echo $song;

	} else {
		echo "Nothing!";
	}

	echo "</b><br>\n";
?>
