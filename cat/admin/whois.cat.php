<?
	if (!defined("VALID")) die;

	switch ($arg1) {
		default:
			?>
			<H2>Whois</H2>
			<FORM METHOD="post" ACTION="<?= $_SERVER['PHP_SELF'] ?>">
			<INPUT TYPE="text" NAME="q" VALUE="<?= htmlspecialchars($_POST['q']) ?>"> &nbsp; <INPUT TYPE="submit" VALUE="Submit">
			</FORM>
			<?

			if (!empty($_POST['q'])) {
				$domain = escapeshellarg(strip_tags($_POST['q']));
				exec("whois $domain", $output);
				echo nl2br(join("\n", preg_replace("/(^[a-z0-9 ]+:)/i", "<B>\\1</B> ", $output)));
			}

			break;
	}

?>
