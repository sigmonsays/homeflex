<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "other":
			$f = eregi_replace("[^a-z0-9.]", "", $arg1);
			$f = str_replace("..", "", $f);
			$lfile = "$localPath/backends/$f";

			if (!file_exists($lfile) && !empty($f)) {
				$f .= ".php";
				$lfile = "$localPath/backends/$f";
			}

			if (file_exists($lfile) && !empty($f)) {
				echo '<H2>News - ' . ucwords(substr($f, 0, strpos($f, "."))) . '</H2>';

				echo "<A HREF=\"$urlPrefix/news\"><< Back</A><BR><BR>\n";
				@include($lfile);

			} else {
				echo '<H2>Invalid Backend</H2>';

				echo "<A HREF=\"$urlPrefix/news\"><< Back</A>";
			}

			break;

		default:
			require("$localPath/inc/headerIncludes.php");

			echo "<H2>News</H2>\n";

			if (empty($start)) $start = 0;
			if (empty($offset)) $offset = 10;

			$dbi->db->query("SELECT * FROM {$dbTables['news']} ORDER BY `when` DESC LIMIT $start, $offset");

			$news_template = join(file("$localPath/inc/news.template"), "");


			echo "<table width=100% border=0 align=center>";


			if (!$dbi->db->num_rows()) {
				echo "<tr><td align=center valign=middle height=100>Sorry, There isn't any news.</td></tr>";
			}

			echo "<tr><td>";
			$rbuf = "";
			while ($posts = $dbi->db->fetch_object()) {

				$buf=str_replace("<!-- %post_caption% -->", $posts->subject, $news_template);
				$buf=str_replace("<!-- %post_date% -->", formatTime($posts->when), $buf);
				$buf=str_replace("<!-- %post_name% -->", $posts->email, $buf);
				$buf=str_replace("<!-- %post% -->", stripslashes($posts->post), $buf);

				$buf=str_replace("<!-- %links% -->",	$links,	$buf);
				$rbuf .= $buf;
			}
			echo $rbuf;
			echo "</td></tr>";
			echo "</table>";

			echo "<BR><BR><A HREF=\"$urlPrefix/news/other/index.php\">Other News...</A><BR>\n";


	}
?>
