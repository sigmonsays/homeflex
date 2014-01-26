<?
	if (!defined("VALID")) die;
	switch ($func) {
		default:
			$q = (isset($_POST['q']) ? $_POST['q'] : "");
			if (empty($q) && !empty($func)) $q = $func;
			?>
			<H2>Whois</H2>
			<FORM METHOD="post" ACTION="<?= $_SERVER['PHP_SELF'] ?>">
			<INPUT TYPE="text" NAME="q" VALUE="<?= htmlspecialchars($q) ?>"> &nbsp; <INPUT TYPE="submit" VALUE="Submit">
			</FORM>
			<?

			if (!empty($q)) {
				$domain = escapeshellarg(strip_tags($q));
				exec("whois $domain", $output);
				echo nl2br(join("\n", preg_replace("/(^[a-z0-9 ]+:)/i", "<B>\\1</B> ", $output)));
			} else {
				?>
				You can also type the domain right after the URL. For example, entering:<P>
				&nbsp; http://<?= $_SERVER['SERVER_NAME'] ?>/whois/<B>mydomain.org</B> 

				<P>
				Would automatically look up <B>mydomain.org</B>. Combine this with <B>?nocontent=1</B> and it makes
				easier parsing.
				<?
			}

			break;
	}

