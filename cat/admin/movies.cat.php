<?
	if (!defined("VALID")) die;

	switch ($arg1) {
		case "delete":
			$form = new formInput;

			$sql = $dbi->db->query("SELECT id, title FROM {$dbTables['movies']} WHERE id='$arg2'");
			if ($dbi->db->num_rows($sql)) {
				list($id, $title) = $dbi->db->fetch_row($sql);
				$removeCover = 0;
				?>
				<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/movies/deleteSubmit/$arg2" ?>">
				<? $form->hidden("id", $id) ?>
				<TABLE WIDTH="40%" BORDER=0 ALIGN="center">
				<TR><TD COLSPAN=2><H2>Delete Options</H2></TD></TR>

				<TR>
					<TD>Remove cover from disk</TD>
					<TD><? $form->boolean("removeCover") ?></TD>
				</TR>
				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Delete"></TD></TR>
				</TABLE>
				</FORM>
				<?
			} else {
				list($arg1, $arg2, $arg3) = array("TITLE", "ASC", $arg3);
				require("cat/$cat.cat.php");
			}
			break;


		case "deleteSubmit":
			$id = intval($_POST['id']);
			$removeCover = $_POST['removeCover'];

			if ($removeCover) {
				if (file_exists("$localMoviePath/$id.jpg")) {
					@$r = unlink("$localMoviePath/$id.jpg");
					if (!$r) echo "Couldn't delete cover, most likely permission denied.<BR>";
				} else
					echo "Couldn't find movie cover!<BR>";
			}

			$sql = $dbi->db->query("DELETE FROM {$dbTables['movies']} WHERE id='$id'");
			if (!$sql)
				echo "Failed to delete movie id #$id!<BR>";
			list($arg1, $arg2, $arg3) = array("TITLE", "ASC", $arg2);
			require("cat/$cat.cat.php");
			break;

		case "edit":
			$form = new formInput;

			$sql = $dbi->db->query("SELECT * FROM {$dbTables['movies']} WHERE id='$arg2'");
			if ($sql && $dbi->db->num_rows($sql)) {
				$movie = $dbi->db->fetch_assoc($sql);
				extract($movie, EXTR_PREFIX_ALL, "movie");
				?>
				<H2>Edit Movie</H2>
				<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/movies/editSubmit/$arg3" ?>">
				<? $form->hidden("movie_id", $movie_id) ?>
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Title</TD>
					<TD><? $form->text("movie_title", $movie_title, 50) ?></TD>
				</TR>
				<TR>
					<TD>Year Released</TD>
					<TD><? $form->text("movie_year", $movie_year) ?></TD>
				</TR>
				<TR>
					<TD>Rating</TD>
					<TD><?
						$ratings = array('NR'=>'NR', 'G'=>'G', 'PG'=>'PG', 'PG-13'=>'PG-13', 'R'=>'R', 'Adult'=>'Adult');
						$form->select("movie_rated", $ratings, $movie_rated);
					?></TD>
				</TR>
				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR>
					<TD>Resize Cover <SMALL>(<I>90x140</I>)<BR>Must be JPG or PNG if resizing!</SMALL></TD>
					<TD><? $form->boolean("movieCoverResize", $movieCoverResize) ?></TD>
				<TR>
					<TD>Cover</TD>
					<TD><? $form->file("movie_cover") ?></TD>
				</TR>
				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR>
					<TD>Director</TD>
					<TD><? $form->text("movie_director", $movie_director) ?></TD>
				</TR>
				<TR>
					<? $movie_starring = "$movie_starring1, $movie_starring2, $movie_starring3"; ?>
					<TD>Starring <SMALL>(<I>Seperate with commas</I>)</SMALL></TD>
					<TD><? $form->text("movie_starring", $movie_starring, 40) ?></TD>
				</TR>
				<TR>
					<TD VALIGN="top">Summary</TD>
					<TD><? $form->textarea("movie_summary", 10, 50, $movie_summary) ?></TD>
				</TR>
				<TR>
					<TD>Genre</TD>
					<TD><?
						// This is terrible, I need a better way of tracking Genres.
						// I shouldn't be querying all the movie entries to get a list of genres =)
						$sql = $dbi->db->query("SELECT DISTINCT genre FROM {$dbTables['movies']} ORDER BY genre ASC");
						$genres = array();
						while ($sql && list($genre) = $dbi->db->fetch_row($sql))
							$genres[$genre] = $genre;
	
						$form->select("movie_genre", $genres, $movie_genre);
					?></TD>
				</TR>
				<TR>
					<TD>Size <SMALL>(<I>eg 5M, 2k, 1G</I>)</SMALL></TD>
					<TD><? $form->text("movie_size", $movie_size) ?></TD>
				</TR>

				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Update"></TD></TR>
				</TABLE>
				</FORM>
				<?
			} else {
				list($arg1, $arg2, $arg3) = array("TITLE", "ASC", $arg2);
				require("cat/$cat.cat.php");
			}
			break;

		case "editSubmit":
			$movie_id = $_POST['movie_id'];
			$movie_title = $_POST['movie_title'];
			$movie_year = $_POST['movie_year'];
			$movie_rated = $_POST['movie_rated'];
			$movie_cover = $_POST['movie_cover'];
			$movie_director = $_POST['movie_director'];
			$movie_starring = $_POST['movie_starring'];
			$movie_summary = $_POST['movie_summary'];
			$movie_genre = $_POST['movie_genre'];
			$movie_size = $_POST['movie_size'];
			$movieCoverResize = $_POST['movieCoverResize'];

			if ($movieCoverResize) {
				if (is_uploaded_file($movie_cover)) {
					@list($width, $height, $type) = getimagesize($movie_cover);
					$error = 0;
					if ($type == 2) {       //jpg
						$image = imageCreateFromJPEG($categoryPicture);
					} elseif ($type == 3) { //png
						$image = imageCreateFromPNG($categoryPicture);
					} else {
						echo "You uploaded a {$imageTypes[$type]}! I will only accept JPEG or PNG.<BR>";
						$error = 1;
					}

					if (!$error) {
						$coverFile = $movie_id;
						$imgDest = imageCreate(90, 140);
						imagecopyresampled($imgDest, $image, 0, 0, 0, 0, 90, 140, $width, $height);
						imagePNG($imgDest, MOVIE_COVER_LOCAL_PATH . "/$coverFile.png");
						imageDestory($image);
						imageDestory($imgDest);
					}
				}
			}

			
			
			$movie_title = $dbi->db->escape_string($movie_title);
			$movie_year = (int)$movie_year;
			$movie_director = $dbi->db->escape_string($movie_director);
			list($starring1, $starring2, $starring3) = split(",", $dbi->db->escape_string($movie_starring));
			$movie_summary = $dbi->db->escape_string($movie_summary);
			$size = textSize($movie_size);
			
			$sql = $dbi->db->query("UPDATE {$dbTables['movies']} SET title='$movie_title', year='$movie_year', rated='$movie_rated', "
				. "director='$movieDirector', starring1='$starring1', starring2='$starring2', starring3='$starring3', "
				. "summary='$movie_summary', genre='$movie_genre', size='$size' WHERE id='$movie_id'");
			if ($sql) {
				list($arg1, $arg2, $arg3) = array("TITLE", "ASC", $arg2);
				require("cat/$cat.cat.php");
			} else {
				echo "There was an error updating! This is a problem with the database!<BR>";
				$arg1 = "edit";
				require("cat/$cat.cat.php");
			}
			break;

		case "add":
			$form = new formInput;

			?>
			<H2>Add New Movie</H2>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/movies/addSubmit" ?>">

			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<TR>
				<TD>Title</TD>
				<TD><? $form->text("movieTitle") ?></TD>
			</TR>
			<TR>
				<TD>Year Released</TD>
				<TD><? $form->text("movieYear") ?></TD>
			</TR>
			<TR>
				<TD>Rating</TD>
				<TD><?
					$ratings = array('NR'=>'NR', 'G'=>'G', 'PG'=>'PG', 'PG-13'=>'PG-13', 'R'=>'R', 'Adult'=>'Adult');
					$form->select("movieRating", $ratings);
				?></TD>
			</TR>

			<TR><TD COLSPAN=2>&nbsp;</TD></TR>
			<TR>
				<TD>Resize Cover <SMALL>(<I>90x140</I>)<BR>Must be JPG or PNG if resizing!</SMALL></TD>
				<TD><? $form->boolean("movieCoverResize") ?></TD>
			<TR>
				<TD>Cover</TD>
				<TD><? $form->file("movieCover") ?></TD>
			</TR>
			<TR><TD COLSPAN=2>&nbsp;</TD></TR>

			<TR>
				<TD>Director</TD>
				<TD><? $form->text("movieDirector", "", 30) ?></TD>
			</TR>
			<TR>
				<TD>Starring <SMALL>(<I>Seperate with commas</I>)</SMALL></TD>
				<TD><? $form->text("movieStarring", "", 50) ?></TD>
			</TR>
			<TR>
				<TD VALIGN="top">Summary</TD>
				<TD><? $form->textarea("movieSummary", 10, 50) ?></TD>
			</TR>
			<TR>
				<TD>Genre</TD>
				<TD><?
					// This is terrible, I need a better way of tracking Genres.
					// I shouldn't be querying all the movie entries to get a list of genres =)
					$sql = $dbi->db->query("SELECT DISTINCT genre FROM {$dbTables['movies']} ORDER BY genre ASC");
					$genres = array();
					while ($sql && list($genre) = $dbi->db->fetch_row($sql))
						$genres[] = $genre;

					$form->select("movieGenre", $genres, $movieGenre);
				?></TD>
			</TR>
			<TR>
				<TD>Size <SMALL>(<I>eg 5M, 2k, 1G</I>)</SMALL></TD>
				<TD><? $form->text("movieSize") ?></TD>
			</TR>

			<TR><TD COLSPAN=2>&nbsp;</TD></TR>
			<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Add Movie"></TD></TR>
			</TABLE>
			</FORM>
			<?
			break;


		case "addSubmit":
			$movieTitle = $_POST['movieTitle'];
			$movieYear = $_POST['movieYear'];
			$movieRating = $_POST['movieRating'];
			$movieCoverResize = $_POST['movieCoverResize'];
			$movieDirector = $_POST['movieDirector'];
			$movieStarring = $_POST['movieStarring'];
			$movieSummary = $_POST['movieSummary'];
			$movieGenre = $_POST['movieGenre'];
			$movieSize = $_POST['movieSize'];

			list($movieStarring1, $movieStarring2, $movieStarring3) = split(",", $movieStarring);

			$sql = $dbi->db->query(
				"INSERT INTO {$dbTables['movies']} VALUES(NULL, '$movieTitle', '$movieYear', '$movieRating', "
				. "'$movieDirector', '$movieStarring1', '$movieStarring2', '$movieStarring3', NULL, "
				. "'$movieSummary', '$movieGenre', '$movieSize')");

			if ($sql) {
				if ($movieCoverResize) {
					if (is_uploaded_file($movieCover)) {
						$coverFile = $dbi->db->insert_id();
						$error = 0;
						@list($width, $height, $type) = getImageSize($movieCover);
						if ($type == 2) {       //jpg
							$image = imageCreateFromJPEG($categoryPicture);
						} elseif ($type == 3) { //png
							$image = imageCreateFromPNG($categoryPicture);
						} else {
							echo "You uploaded a {$imageTypes[$type]}! I will only accept JPEG or PNG.<BR>";
							$error = 1;
						}
                                
						if (!$error) {
							$imgDest = imageCreate(90, 140);
							imagecopyresampled($imgDest, $image, 0, 0, 0, 0, 90, 140, $width, $height);
							imagePNG($imgDest, "$movieCoverLocalPath/$coverFile.png");
							imageDestory($image);
							imageDestory($imgDest);
						}
					} else
						echo "WARNING: No cover uploaded<BR>";
				}
			} else
				echo "There was a database error while attempting to add this movie!<BR>";

			unset($arg1);
			require("cat/$cat.cat.php");
			break;

		case "search":
			?>
			<A HREF="<?= $urlPrefix ?>/admin/movies">Browse</A>
			<H2>Search</H2>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/movies/searchResults" ?>">
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
				<TD><INPUT TYPE="text" NAME="directory"></TD>
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

			$start = $arg2;
			$start = $dbi->db->escape_string($start);
			if (empty($start)) $start = 0;
			$offset = 20;

			if (!empty($arg3)) {
				list($title, $rated, $year,$directory,$starring,$summary,$genre) = split(":", base64_decode($arg3));
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

			if (empty($title)) 	$error++; else $qTitle = 	"OR title LIKE '$a$title$b'";
			if (empty($rated)) 	$error++; else $qRated = 	"OR rated='$rated'";
			if (empty($year))  	$error++; else $qYear = 	"OR year LIKE '$a$year$b'";
			if (empty($director)) 	$error++; else $qDirector = 	"OR director LIKE '$a$directory$b'";
			if (empty($starring))	$error++; else $qStarring =	"OR starring1 LIKE '$a$starring$b'";
			if (empty($summary))	$error++; else $qSummary =	"OR summary LIKE '$a$summary$b'";
			if (empty($genre))	$error++; else $qGenre =	"OR genre='$genre'";

			if ($error < 7) {
				$qry = "SELECT id,title FROM {$dbTables['movies']} "
					. "WHERE 0 $qTitle $qRated $qYear $qDirector $qStarring $qSummary $qGenre "
					. "LIMIT $start, $offset";

				$sql = $dbi->db->query($qry);

				if ($sql && $dbi->db->num_rows($sql)) {
					$count = $dbi->db->num_rows($sql);
					?>
					<H2>Search Results</H2>
					<TABLE WIDTH="90%" BORDER=0 ALIGN="center" CELLSPACING=0 CELLPADDING=2>
					<TR>
						<TD>&nbsp;</TD>
						<TD><B>Movie</B></TD>
						<TD>&nbsp;</TD>
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
							echo "<TD><A HREF=\"$urlPrefix/admin/movies/id/$id\">$title</A></TD>";
							echo "<TD ALIGN=\"center\">";
								echo "<A HREF=\"$urlPrefix/admin/movies/edit/$id\">edit</A> &nbsp; ";
								echo "<A HREF=\"$urlPrefix/admin/movies/delete/$id\">delete</A>";
							echo "</TD>";
						echo "</TR>";
					}

					echo "<TR><TD COLSPAN=3 ALIGN=\"right\">\n";
						$nstart = $start + $offset;
						$pstart = $start - $offset;

						if ($pstart >= 0)
							echo "<A HREF=\"$urlPrefix/admin/movies/searchResults/$pstart/$search\"><< Previous $offset</A>";
						else
							echo "<< Previous $offset";
						echo " &nbsp; ";
						if ($count == $offset)
							echo "<A HREF=\"$urlPrefix/admin/movies/searchResults/$nstart/$search\">Next $offset >>";
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
				$arg1 = "search";
				require("cat/$cat.cat.php");
			}
			break;

		case "id":
			$sql = $dbi->db->query("SELECT * FROM {$dbTables['movies']} WHERE id='$arg2'");
			if ($sql && $dbi->db->num_rows($sql)) {
				$movie = $dbi->db->fetch_object($sql);
				echo "<H2>Movies - $movie->title</H2>";
                
				$template = join("", file("movies.template"));
				$buf=str_replace("%id%", $movie->id, $template);
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
				list($arg1, $arg2, $arg3) = array("TITLE", "ASC", 0);
				require("cat/$cat.cat.php");
			}
			break;

		default:
			//url syntax: /movies/field/order/start
			$field = $arg1;
			$order = $arg2;
			$start = $arg3;

			if (empty($start)) $start = 0;
			if (empty($field)) $field = "title";
			if (empty($order)) $order = "ASC";
			$offset = 20;

			$sql = $dbi->db->query("SELECT COUNT(id) FROM {$dbTables['movies']}");
			if ($sql) list($Totalcount) = $dbi->db->fetch_row($sql);

			?>
			<A HREF="<?= $urlPrefix ?>/admin/movies/search">Search</A> &nbsp;
			<A HREF="<?= $urlPrefix ?>/admin/movies/add">Add Movie</A>
			<H2>Movies</H2>
			I currently have <B><?= $Totalcount ?></B> movies. I've been collecting them for a while now. Most of them are Divx
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
					echo "<TD><A HREF=\"$urlPrefix/admin/movies/title/$otherOrder\">Movie $orderPic</A></TD>";
				else
					echo "<TD><A HREF=\"$urlPrefix/admin/movies/title/$otherOrder\">Movie</A></TD>";

				if ($field == "rated")
					echo "<TD><A HREF=\"$urlPrefix/admin/movies/rated/$otherOrder\">Rated $orderPic</A></TD>";
				else
					echo "<TD><A HREF=\"$urlPrefix/admin/movies/rated/$otherOrder\">Rated</A></TD>";

				if ($field == "year")
					echo "<TD><A HREF=\"$urlPrefix/admin/movies/year/$otherOrder\">Released $orderPic</A></TD>";
				else
					echo "<TD><A HREF=\"$urlPrefix/admin/movies/year/$otherOrder\">Released</A></TD>";
			echo "<TD>&nbsp;</TD></TR>\n";

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
						echo "<TD><A HREF=\"$urlPrefix/admin/movies/id/$movie->id\">$movie->title</A></TD>";
						echo "<TD>$movie->rated</TD>";
						echo "<TD>$movie->year</TD>";
						echo "<TD ALIGN=\"center\">";
							echo "<A HREF=\"$urlPrefix/admin/movies/edit/$movie->id/$start\">edit</A> &nbsp; ";
							echo "<A HREF=\"$urlPrefix/admin/movies/delete/$movie->id/$start\">delete</A>";
						echo "</TD>";
					echo "</TR>\n";
				}


				echo "<TR><TD COLSPAN=5 ALIGN=\"right\">\n";
				$nstart = $start + $offset;
				$pstart = $start - $offset;
				if ($pstart >= 0)
					echo "<< <A HREF=\"$urlPrefix/admin/movies/$field/$order/$pstart\">Previous $offset Movies</A>";
				else
					echo "<< Previous $offset Movies";
				echo " &nbsp; ";
				if ($count >= $offset)
					echo "<A HREF=\"$urlPrefix/admin/movies/$field/$order/$nstart\">Next $offset Movies</A> >>";
				else
					echo "Next $offset Movies >>";
				echo "</TD></TR>\n";

			} else
				echo "<TR><TD COLSPAN=5 ALIGN=\"center\">No Movies!</TD></TR>\n";
			echo "</TABLE>\n";
			break;
	}
?>
