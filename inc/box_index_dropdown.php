<script LANGUAGE="javascript1.1">
	function index_dropdown_submit(f) {
		var s;
		s = f.index_dd_category.value;
		if (s.substring(0, 4) == "url:") {
			document.location = s.substring(4, s.length);

		} else {
			f.submit();
		}
	}
</script>

<form NAME="catForm" METHOD="post" ACTION="<?= $_SERVER['REQUEST_URI'] ?>" OnChange="index_dropdown_submit(this);">

	<select NAME="index_dd_category">
<?
	$mainToolbar = load_toolbar();

	while ($link = $mainToolbar->iterate()) {
		$tcat = strrchr($link->url, '/');

		if ($cat == substr($tcat, 1)) $sel = "SELECTED"; else $sel = "";

		echo "<OPTION $sel VALUE=\"url:$link->url\">$link->title</OPTION>\n";
	}

	unset($mainToolbar);
	
?>

	</select> &nbsp; <input type=button value='[OK]' onClick="index_dropdown_submit(this);">
</form>
