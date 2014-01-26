<?
	if (!defined("VALID")) die;

	unset($output);
	exec("/usr/games/gfortune 2>/dev/null", $output);
	$quote = strip_tags(join("<BR>", $output), "<BR>");

	?>
	<TABLE ALIGN="center" BORDER=0>
	<TR><TD><I><?= $quote ?></I></TD></TR>
	</TABLE>
	<?
?>
