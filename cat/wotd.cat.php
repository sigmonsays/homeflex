<H2>Word of the Day</H2>
<?
	@$data = join("", file("/var/qmail/popboxes/wotd/wotd.txt"));
	if ($data) {
		echo '<PRE><FONT SIZE=+1>' .  htmlspecialchars($data) . '</FONT></PRE>';
	} else {
		echo "Sorry but the <B>Word of the day</B> is currently unavailable.";
	}
?>
