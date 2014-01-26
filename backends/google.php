<?
	if (!defined("VALID")) die;

	$files = loadDirectory("$localPath/backends/arch/google");

	foreach($files as $file) {

		$name = ucwords(basename($file, ".xml"));
		echo "<H3>$name</H3>\n";

		@$data = join("", file("$localPath/backends/arch/google/$file"));

		echo $data;
	}

?>
