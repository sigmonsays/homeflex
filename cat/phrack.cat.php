<?
	if (!defined("VALID")) die;

	define("DATA_DIR", "$localPath/phracks");
	define("LIST_COLUMNS", 3);
	$file_types = array('dir' => "Issue", 'file' => "Phile", 'link' => "Links");


	function is_valid_path($base, $sub) {
		$l = strlen($base);
		$tmp_path = realpath($base . "/" . $sub);
		if (strncmp($base, $tmp_path, $l) == 0) return TRUE;
		return FALSE;
	}

	function safe_path_string($str) {
		$str = str_replace("..", "", $str);
		return $str;
	}

	function variable($var) {
		if (isset($_GET[$var])) {
			return $_GET[$var];
		} else {
			return $_POST[$var];
		}
	}

	function load_file_array($path) {
		$data = array();
		@$d = opendir($path);
		if (!$d) return $data;

		readdir($d);
		readdir($d);

		while ($f = readdir($d)) {
			$type = filetype("$path/$f");
			if ($type == 'dir' && file_exists("$path/$f/.skip")) continue;
			if ($type == 'file' && $f == 'index.php') continue;
			$data[$type][] = $f;
		}
		closedir($d);

		ksort($data);

		return $data;
	}


	$resolved_data_dir = realpath(DATA_DIR);
	$sub = variable("path");
	$path = realpath($resolved_data_dir . $sub);

	if (!is_valid_path($resolved_data_dir, $sub)) {
		echo "<TITLE>ERROR (check): Invalid Path</TITLE>\n";
		die("ERROR (check): Invalid Path");
	}


	echo "<H2>Phrack " . htmlspecialchars($_GET['path']) . "</H2>\n";

	echo "<A HREF=\"" . "$urlPrefix/phrack" . "\">[ MAIN ]</A> /";
	if (!empty($sub)) {
		/* build nav link bar thingy-ma-bobber */

		$ar = split("/", $sub);
		$c = count($ar);
		for($i=0; $i<$c; $i++) {

			$slice = array_slice($ar, 1, $i);
			$new_path = join("/", $slice);

			if ( filetype("$resolved_data_dir/$new_path") != 'dir') continue;

			$x = count($slice);
			$name = $slice[$x - 1];
			if (empty($name)) continue;
			echo " <A HREF=\"$urlPrefix/phrack/?path=/" . $new_path . "\">$name</A> /";
		}

	}
	echo "<BR><BR>\n";


switch ($func) {

	default:
		?>
		<TABLE WIDTH=100% BORDER=0>
		<?
			$data = load_file_array($path);

			foreach($data as $file_type => $file_array) {

				echo "<TR><TD COLSPAN=" . LIST_COLUMNS . "><H2>" . $file_types[$file_type] . "</H2></TD></TR>\n";

				echo "<TR>\n";
				$c = 0;
				foreach($file_array as $file) {


					echo "<TD>";
					if ($file_type == 'dir') {
						echo "<A HREF=\"$urlPrefix/phrack?path=$sub/$file\">$file</A>";

					} else if ($file_type == 'file') {
						echo "<A HREF=\"$urlPrefix/phrack/read$sub/$file/?path=$sub/$file\">$file</A>";

					} else {
						echo $file;
					}

					if ($file_type == 'link') {
						$rfile = realpath("$path/$file");
						$tmp_type = filetype($rfile);
						echo "&nbsp; <I>(" . $rfile . ")</I> $tmp_type";
					}

					echo "</TD>\n";

					if ((++$c % LIST_COLUMNS) == 0) echo "</TR><TR>";

				}

				echo "</TR>";
			}
		?>
		</TABLE>
		<?
		break;

	case "read":
		
		$phrack_file = safe_path_string("$arg1/$arg2");
		$f = "$localPath/phracks/$phrack_file";
		@$data = implode("", file($f));
		if ($data) {
			echo "<H3>$phrack_file</H3>\n";
			echo "<PRE>";

			echo $data;

			echo "</PRE>";

		} else {
			echo "<A HREF=\"$urlPrefix/phrack\">Phrack Main</A><BR>\n";
		}
		break;
}

?>
