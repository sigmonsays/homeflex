<?
	if (!defined("VALID")) die;
	switch ($func) {

		case "search":
			?>
			<A HREF="<?= $urlPrefix ?>/movies">Browse</A>
			<H2>Search</H2>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/movies/searchResults" ?>">
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<TR>
				<TD COLSPAN=5>
				<input type="radio" name="match" value="exact">Exact Match
				<input type="radio" name="match" value="substring" checked>Sub-String
				<input type="radio" name="match" value="startswith">Starts With
				<input type="radio" name="match" value="endswith">Ends With
				<BR><BR>
				</TD>
			</TR>
			<TR>
				<TD>Title</TD>
				<TD><INPUT TYPE="text" NAME="title"></TD>

				<TD>&nbsp;</TD>

				<TD>Rated</TD>
				<TD><SELECT NAME="rated">
					<OPTION></OPTION>
					<OPTION>G</OPTION>
					<OPTION>PG</OPTION>
					<OPTION>PG-13</OPTION>
					<OPTION>R</OPTION>
					<OPTION>NR</OPTION>
					<OPTION>Adult</OPTION>
				</SELECT></TD>
			</TR>

			<TR>
				<TD>Year Released</TD>
				<TD><INPUT TYPE="text" NAME="year" SIZE=5 MAXLENGTH=4></TD>

				<TD>&nbsp;</TD>

				<TD>Director</TD>
				<TD><INPUT TYPE="text" NAME="director"></TD>
			</TR>

			<TR>
				<TD>Starring</TD>
				<TD><INPUT TYPE="text" NAME="starring"></TD>

				<TD>&nbsp;</TD>

				<TD>Summary Keywords</TD>
				<TD><INPUT TYPE="text" NAME="summary"></TD>
			</TR>

			<TR>
				<TD>Genre</TD>
				<TD><SELECT NAME="genre"><OPTION></OPTION>
				<?
					$sql = $dbi->db->query("SELECT DISTINCT genre FROM {$dbTables['movies']}");
					while ($sql && list($genre) = $dbi->db->fetch_row($sql)) {
						if (!empty($genre)) echo "<OPTION>$genre</OPTION>";
					}
				?>
				</SELECT></TD>

				<TD COLSPAN=3 ALIGN="center">&nbsp;</TD>
			</TR>
			<TR><TD COLSPAN=5 ALIGN="center"><INPUT TYPE="submit" VALUE="Search"></TD></TR>

			</TABLE>
			</FORM>
			<?
			break;

		case "searchResults":
			// /home/movies/search/$start/base64_search_shit

			$match = $_POST['match'];
			$title = $_POST['title'];
			$rated = $_POST['rated'];
			$year = $_POST['year'];
			$director = $_POST['director'];
			$starring = $_POST['starring'];
			$summary = $_POST['summary'];
			$genre = $_POST['genre'];

			$start = $arg1;
			$start = $dbi->db->escape_string($start);
			if (empty($start)) $start = 0;
			$offset = 20;

			if (!empty($arg2)) {
				list($title, $rated, $year,$directory,$starring,$summary,$genre) = split(":", base64_decode($arg2));
			}

			if ($match == "substring") {
				$a = "%"; $b = "%";
			} elseif ($match == "exact") {
				$a = ""; $b = "";
			} elseif ($match == "startswith") {
				$a = ""; $b = "%";
			} elseif ($match == "endswith") {
				$a = "%"; $b = "";
			}

			$search = base64_encode("$title:$rated:$year:$director:$starring:$summary:$genre");

			unset($qTitle, $qRated, $qYear, $qDirector, $qStarring, $qSummary, $qGenre);
			$title = $dbi->db->escape_string($title);
			$rated = $dbi->db->escape_string($rated);
			$year = $dbi->db->escape_string($year);
			$director = $dbi->db->escape_string($director);
			$starring = $dbi->db->escape_string($starring);
			$summary = $dbi->db->escape_string($summary);
			$genre = $dbi->db->escape_string($genre);
			$searchCritera = "";

			if (empty($title))
				$error++;
			else {
				$qTitle = "AND title LIKE '$a$title$b'";
				$searchCritera .= "Title is like $title, ";
			}

			if (empty($rated))
				$error++;
			else {
				$qRated = "AND rated='$rated'";
				$searchCritera .= "Rated $rated, ";
			}

			if (empty($year))
				$error++;
			else {
				$qYear = "AND year LIKE '$a$year$b'";
				$searchCritera .= "Released in $year";
			}

			if (empty($director))
				$error++;
			else {	
				$qDirector = "AND director LIKE '$a$director$b'";
				$searchCritera .= "Director is like $director, ";
			}

			if (empty($starring))
				$error++;
			else {
				$qStarring = "AND starring1 LIKE '$a$starring$b'";
				$searchCritera .= "Starring actor is like $starring, ";
			}

			if (empty($summary))
				$error++;
			else {
				$qSummary = "AND summary LIKE '$a$summary$b'";
				$searchCritera .= "Summary contains $summary, ";
			}

			if (empty($genre))
				$error++;
			else {
				$qGenre = "AND genre='$genre'"; 
				$searchCritera .= "Genre is $genre";
			}

			if ($error < 7) {
				$qry = "SELECT id,title FROM {$dbTables['movies']} "
					. "WHERE 1 $qTitle $qRated $qYear $qDirector $qStarring $qSummary $qGenre "
					. "LIMIT $start, $offset";

				$sql = $dbi->db->query($qry);

				if ($sql && $dbi->db->num_rows($sql)) {
					$count = $dbi->db->num_rows($sql);
					?>
					<H2>Search Results</H2>
					<TABLE WIDTH="90%" BORDER=0 ALIGN="center" CELLSPACING=0 CELLPADDING=2>
					<TR><TD COLSPAN=2>
					<B>Search Critera:</B> <?= $searchCritera ?>
					<BR><BR>
					</TD></TR>

					<TR>
						<TD>&nbsp;</TD>
						<TD><B>Movie</B></TD>
					</TR>
					<?
					$c = 0;
					$x = $start;

					while (list($id, $title) = $dbi->db->fetch_row($sql)) {
						$x++;
						$c = !$c;
						if ($c) $cl = "row1"; else $cl = "row2";
						echo "<TR CLASS=\"$cl\">";
							echo "<TD>$x</TD>";
							echo "<TD><A HREF=\"$urlPrefix/movies/id/$id\">$title</A></TD>";
						echo "</TR>";
					}

					echo "<TR><TD COLSPAN=2 ALIGN=\"right\">\n";
						$nstart = $start + $offset;
						$pstart = $start - $offset;

						if ($pstart >= 0)
							echo "<A HREF=\"$urlPrefix/movies/searchResults/$pstart/$search\"><< Previous $offset</A>";
						else
							echo "<< Previous $offset";
						echo " &nbsp; ";
						if ($count == $offset)
							echo "<A HREF=\"$urlPrefix/movies/searchResults/$nstart/$search\">Next $offset >>";
						else
							echo "Next $offset >>";
					?>
					</TD></TR>
					</TABLE>
					<?
				} else 
					echo "No Search Results.. Please expand your search.";
				echo $dbi->db->error();
			} else {
				echo "You must fill out atleast one field!<BR>";
				$func = "search";
				require("cat/$cat.cat.php");
			}
			break;

		case "id":
			$sql = $dbi->db->query("SELECT * FROM {$dbTables['movies']} WHERE id='$arg1'");
			if ($sql && $dbi->db->num_rows($sql)) {
				$movie = $dbi->db->fetch_object($sql);
                
				$movie_template = join("", file("$localPath/inc/movies.template"));
				$buf=str_replace("%id%", $movie->id, $movie_template);
				$buf=str_replace("%title%", $movie->title, $buf);
				$buf=str_replace("%year%", $movie->year, $buf);
				$buf=str_replace("%rated%", $movie->rated, $buf);
				$buf=str_replace("%director%", $movie->director, $buf);
				$buf=str_replace("%starring%", $movie->starring1, $buf);
				$buf=str_replace("%datecreated%", formatTime($movie->datecreated), $buf);
				$buf=str_replace("%summary%", $movie->summary, $buf);
				$buf=str_replace("%picture%", "<IMG SRC=\"" . MOVIE_COVER_URL_PREFIX . "/$movie->id.jpg\">", $buf);
				echo $buf;
			} else {
				unset($func);
				require("cat/$cat.cat.php");
			}
			break;

		default:
			//url syntax: /movies/field/order/start
			$field = $func;
			$order = $arg1;
			$start = $arg2;

			if (empty($start)) $start = 0;
			if (empty($field)) $field = "title";
			if (empty($order)) $order = "ASC";
			$offset = 20;

			$sql = $dbi->db->query("SELECT COUNT(*) FROM {$dbTables['movies']}");
			if ($sql) list($count) = $dbi->db->fetch_row($sql);

			?>
			<A HREF="<?= $urlPrefix ?>/movies/search">Search</A>
			<H2>Movies</H2>
			I currently have <B><?= $count ?></B> movies. I've been collecting them for a while now. Most of them are Divx
			and available for download for the special peoples. If you see something you want and have something you 
			can give me, drop me a line.
			<P>
			<TABLE WIDTH="100%" BORDER=0 ALIGN="center" CELLSPACING=0>
			<TR>
				<TD>&nbsp;</TD>
			<?
				if ($order == "ASC") {
					$otherOrder = "DESC";
					$orderPic = "<IMG SRC=\"" . IMAGES_URL_PREFIX . "/asc_order.gif\" BORDER=0>";
				} else {
					$orderPic = "<IMG SRC=\"" . IMAGES_URL_PREFIX . "/desc_order.gif\" BORDER=0>";
					$otherOrder = "ASC";
				}

				if ($field == "title")
					echo "<TD><A HREF=\"$urlPrefix/movies/title/$otherOrder\">Movie $orderPic</A></TD>";
				else
					echo "<TD><A HREF=\"$urlPrefix/movies/title/$otherOrder\">Movie</A></TD>";

				if ($field == "rated")
					echo "<TD><A HREF=\"$urlPrefix/movies/rated/$otherOrder\">Rated $orderPic</A></TD>";
				else
					echo "<TD><A HREF=\"$urlPrefix/movies/rated/$otherOrder\">Rated</A></TD>";

				if ($field == "year")
					echo "<TD><A HREF=\"$urlPrefix/movies/year/$otherOrder\">Released $orderPic</A></TD>";
				else
					echo "<TD><A HREF=\"$urlPrefix/movies/year/$otherOrder\">Released</A></TD>";
			echo "</TR>\n";

			$field = $dbi->db->escape_string($field);
			$order = $dbi->db->escape_string($order);
			$start = $dbi->db->escape_string($start);
			$sql = $dbi->db->query(
				"SELECT id, title, year, rated, datecreated "
				. "FROM movies "
				. "ORDER BY $field $order "
				. "LIMIT $start, $offset");

			if ($sql && $count = $dbi->db->num_rows($sql)) {


				$c = 0;
				$x = $start;
				while ($movie = $dbi->db->fetch_object($sql)) {
					$x++;
					$c = !$c;
					if ($c) $cl = "row1"; else $cl = "row2";
					echo "<TR CLASS=\"$cl\">";
						echo "<TD>$x</TD>";
						echo "<TD><A HREF=\"$urlPrefix/movies/id/$movie->id\">$movie->title</A></TD>";
						echo "<TD>$movie->rated</TD>";
						echo "<TD>$movie->year</TD>";
					echo "</TR>\n";
				}


				echo "<TR><TD COLSPAN=4 ALIGN=\"right\">\n";
				$nstart = $start + $offset;
				$pstart = $start - $offset;
				if ($pstart >= 0)
					echo "<< <A HREF=\"$urlPrefix/movies/$field/$order/$pstart\">Previous $offset Movies</A>";
				else
					echo "<< Previous $offset Movies";
				echo " &nbsp; ";
				if ($count >= $offset)
					echo "<A HREF=\"$urlPrefix/movies/$field/$order/$nstart\">Next $offset Movies</A> >>";
				else
					echo "Next $offset Movies >>";
				echo "</TD></TR>\n";

			} else
				echo "<TR><TD COLSPAN=4 ALIGN=\"center\">No Movies!</TD></TR>\n";
			echo "</TABLE>\n";
			break;
	}
?>
