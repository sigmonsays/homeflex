<?

	$change = (isset($_POST['change'])) ? $_POST['change'] : $_GET['change'];

	if ($change) {
		$new_skin = (isset($_POST['skin'])) ? $_POST['skin'] : $_GET['skin'];

		if ($loggedIn) {
			setOption("skin", $new_skin);
		} else {
			$options = array('skin' => $new_skin);
			$guestOptions = serialize($options);
			setcookie("guestOptions", $guestOptions, time() + COOKIE_LIFE, COOKIE_PATH, COOKIE_DOMAIN);
		}
		header("Location: $urlPrefix/skins");
	}

	if ($loggedIn) {
		$skin = $opt_skin;
	} else {
		$options = unserialize(stripslashes($_COOKIE['guestOptions']));
		$skin = $options['skin'];
	}

	$sql = $dbi->db->query("SELECT id,name FROM {$dbTables['skins']} WHERE active=1 ORDER BY name ASC");
	$skin_count = 0;
	if ($sql && $dbi->db->num_rows($sql)) {
		$skin_count = $dbi->db->num_rows($sql);
		while (list($skin_id, $skin_name) = $dbi->db->fetch_row($sql)) {
			$skins[$skin_id] = $skin_name;
		}
	}

	switch ($func) {
		case "random":
			reset($skins);
			$s = rand(0, $skin_count);
			for( ; $s > 0 ; $s--) next($skins);
			$id = key($skins);

			header("Location: $urlPrefix/skins/?nocontent=1&change=1&skin=$id\r\n");
			break;

		case "vote":
			unset($qry);
			if ($arg1 == "yes")
				$qry = "UPDATE {$dbTables['skins']} SET good=good+1 WHERE id='$skin'";
			else if ($arg1 == "no")
				$qry = "UPDATE {$dbTables['skins']} SET bad=bad+1 WHERE id='$skin'";

			if (!empty($qry)) $sql = $dbi->db->query($qry);
			header("Location: $urlPrefix/skins");
			break;

		default:
			$form = new formInput;

			echo "<H2>Skin Browser</H2>\n";

			echo "<B>Current Skin:</B> " . ((empty($skins[$skin])) ? "<I>Default</I>" : $skins[$skin]) . "<BR><BR>\n";

			$sql = $dbi->db->query("SELECT good,bad FROM {$dbTables['skins']} WHERE id='$skin'");
			if ($sql && $dbi->db->num_rows($sql)) {
				list($good, $bad) = $dbi->db->fetch_row($sql);
				if ($good || $bad) {
					$overall = $good + -($bad);
					echo "This skin has been voted good <B>$good</B> times and voted bad <B>$bad</B> times. Overall <B>$overall</B><BR>\n";

					echo "<B>Karma:</B> ";
					if ($overall > 0) {
						echo "Good";
					} else {
						echo "Bad";
					}
					echo "<BR><BR>\n";
				} else
					echo "Nobody has voted for this skin.. jesus!<BR><BR>\n";
			}

			echo "<B>Change Skin</B><BR>\n";
			$form->start("$urlPrefix/skins");
			$form->hidden("change", 1);
			$form->hidden("nocontent", 1);
			$form->select("skin", $skins, $skin);
			$form->submit("OK");
			$form->end();
			echo "<BR>\n";

			echo "<B>Random Skin</B><BR>";
			echo "<A HREF=\"$urlPrefix/skins/random?nocontent=1\">Give me a random skin</A><BR><BR>\n";

			echo "<B>Vote for this Skin</B><BR>";
			echo "<I>You can vote as much as you like.. Please don't abuse this *feature*</I><BR><BR>\n";
			echo "This skin is ";
			echo "<A HREF=\"$urlPrefix/skins/vote/yes?nocontent=1\">Good</A>\n";
			echo " or ";
			echo "<A HREF=\"$urlPrefix/skins/vote/no?nocontent=1\">Bad</A>.\n";
			echo "<BR><BR>\n";

			echo "<B>Browse Skins</B><BR>";

			reset($skins);
			for( ;; ) {
				if ($skin == key($skins)) break;
				if (!next($skins)) break;
			}
			

			if (prev($skins)) {
				$pskin = key($skins);
				echo "Previous Skin is <A HREF=\"$urlPrefix/skins/?nocontent=1&change=1&skin=$pskin\">" . $skins[$pskin] . "</A>\n";
				next($skins);
			} else {
				reset($skins);
			}

			echo " &nbsp; ";

			if (next($skins)) {
				$nskin = key($skins);
				echo "Next skin is <A HREF=\"$urlPrefix/skins/?nocontent=1&change=1&skin=$nskin\">" . $skins[$nskin] . "</A>\n";
			}


			echo "<BR><BR>\n";
			break;
	}
?>
