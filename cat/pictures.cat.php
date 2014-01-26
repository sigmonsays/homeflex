<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "random":

			echo "<h2>Random Picture</h2>\n";

			echo "<i>Refresh for another</i><br>\n";

			$qry = "SELECT * FROM ${dbTables['pictures']} ORDER BY RAND() LIMIT 1";

			if (!$dbi->db->query($qry)) {
				echo "<b>ERROR</b>: query failed<br>\n";

				echo $qry;

				if ($loggedIn) echo $dbi->db->error();
				break;
			}

			$pic = $dbi->db->fetch_object();

			$thumb_src = PICTURES_URL_PREFIX . "/" . $pic->thumbnail;
			$img_src = PICTURES_URL_PREFIX . "/" . $pic->picture;
			$img_href = "$urlPrefix/pictures/display/$pic->id?nocontent=1";

			echo "<h3>" . htmlspecialchars(stripslashes($pic->name)) . "</h3>\n";

			echo "<a target=\"_NEW\" href=\"$img_href\"><img src=\"$thumb_src\" border=0></a>\n";

			break;

		case "slideshow":
				$arg1 = intval($arg1);

				if (headers_sent()) {
					echo "<h2>Slideshow</h2>\n";
				?>
					<script language="javascript">
					function picture_slideshow() {
						window.open("<?= "$urlPrefix/pictures/slideshow?nocontent=1" ?>", 
							"slideshow",
							"width=530,height=700,fullscreen=no,toolbar=no,status=no,menubar=no,scrollbars=no,resizable=yes,directories=no,location=no");
					}
				</script>

			Welcome to the <?= $siteTitle ?> Slideshow. Click the button below to make the fun begin.
			<br><br>
			<?
					echo "<input type=\"button\" onClick=\"picture_slideshow()\" value=\"Open Slideshow\">\n";
					break;
				}


			?>
				<html>
				<title><?= $siteTitle ?> - Slidehow</title>

				<script language="javascript1.2">
					function slideshow_update(i) {
						window.location.replace( '<?= "$urlPrefix/pictures/slideshow/" ?>' + i + '?nocontent=1');
					}

					function start_slideshow() {
						setTimeout("slideshow_update(<?= $arg1 + 1 ?>)", 5000);
					}

				</script>

				<body onLoad="start_slideshow();">
				<?

				$qry = "SELECT name,picture,width,height FROM ${dbTables['pictures']} ORDER BY category DESC LIMIT $arg1,1";
				if (!$dbi->db->query($qry)) {
					echo "Error fetching picture<br>\n";
					break;
				}

				if (!$dbi->db->num_rows()) {
					?>
					<script language="javascript1.2">
					alert('You have came to the end of the slide show, It will now restart');
					slideshow_update(0);
					</script>
					<?
					die;
				}
				$max_width = 500;

				list($name, $img_src, $width, $height) = $dbi->db->fetch_row();

				if ($width <  $max_width) {
					$pic_width = $width;
					$pic_height = $height;

				} else {
					$pic_width = $max_width;
					$pic_height = (int) (($max_width * $height) / $width);
				}
				echo "<center>\n";
				echo "<h3>$name</h3>\n";

				echo "<img width=$pic_width height=$pic_height name=\"pic\" src=\"" . PICTURES_URL_PREFIX . "/" . $img_src . "\">\n";
				echo "</center>\n";
				echo "</body>\n";
				echo "</html>\n";
				

				break;

		case "go":

				$q = $arg1;
				$qe = mysql_escape_string($q);

				$qry = "SELECT id,name FROM ${dbTables['pictures_cat']} WHERE name LIKE '%$qe%'";

				if (!$dbi->db->query($qry)) {
					echo "<b>ERROR</b>: Query failed<br>\n";
					if ($loggedIn) echo $dbi->db->error();
					break;
				}

				$total = $dbi->db->num_rows();

				if ($total == 0) {
					echo "No records found<br>\n";

				} else if ($total == 1) {

					list($id, $name) = $dbi->db->fetch_row();
					jsRedir("$urlPrefix/pictures?node=$id");

				} else { // too many records, display matches

					echo "<H2>Multiple records found</h2>\n";

					while (list($id, $name) = $dbi->db->fetch_row()) {
						echo "<li><a href=\"$urlPrefix/pictures?node=$id\">$name</a></li>\n";
					}

				}

				break;

		case "search":
			if ($arg1 == 'submit') {

				$q = $_GET['q'];
				$qe = mysql_escape_string($q);

				echo "<h2>Search Results</h2>\n";

				if (empty($q)) {
					echo "I can't find anything given nothing<br>\n";
					unset($arg1);
					require(__FILE__);
					break;
				}

				if (strlen($q) <= 3) {
					echo "You need to type in more than 3 charecters<br>\n";
					unset($arg1);
					require(__FILE__);
					break;
				}

				$qry = "SELECT *, "

					. "${dbTables['pictures']}.id AS picture_id, "
					. "${dbTables['pictures']}.name AS picture_name, "
					. "${dbTables['pictures_cat']}.name AS category_name "

					. "FROM ${dbTables['pictures']}  "
					. "LEFT JOIN ${dbTables['pictures_cat']} ON ${dbTables['pictures']}.category = ${dbTables['pictures_cat']}.id "
					. "WHERE ${dbTables['pictures']}.name LIKE '%$qe%' "
					. "OR ${dbTables['pictures']}.description LIKE '%$qe%' "
					. "OR ${dbTables['pictures_cat']}.name LIKE '%$qe%' "
					. "OR ${dbTables['pictures_cat']}.description LIKE '%$qe%'" ;

				if (!$dbi->db->query($qry)) {
					echo "<b>ERROR</b>: the query failed!<br>\n";

					echo "'$qry'<br><br>\n";
					echo $dbi->db->error();
					break;
				}

				$total = $dbi->db->num_rows();

				if (!$total) {
					echo "Nothing found, try specifying a less strict search criteria<br>\n";
					unset($arg1);
					require(__FILE__);
					break;
				}

				echo "<h3>Found $total records</h3>\n";

				echo "<table width=100% border=0 align=center cellpadding=0 cellspacing=0>\n";

				while ($pic = $dbi->db->fetch_object()) {

					$img_src = PICTURES_URL_PREFIX . "/" . $pic->thumbnail;
					$img_url = "$urlPrefix/pictures/display/$pic->picture_id?nocontent=1";
	
					echo "<tr>";
						echo "<td><b>" . stripslashes($pic->category_name) . " / " . stripslashes($pic->picture_name) . "</b></td>";
						echo "<td width=90 align=right>Seen $pic->view_count times</td>";
					echo "</tr>\n";

					echo "<tr>\n";
						echo "<td style=\"border-top: 1px solid black;\" valign=top colspan=2>\n";

							echo "<table width=100% border=0 align=center cellspacing=0 cellpadding=2>\n";
							echo "<tr>\n";
								echo "<td valign=top width=1>";
									echo "<a target=_NEW href=\"$img_url\"><br><img style=\"border: 1px solid black;\" src=\"$img_src\" border=0></a>";
								echo "</td>\n";

								echo "<td valign=top><br>";
									echo empty($pic->description) ? "<i>No description</i>" : htmlspecialchars(stripslashes($pic->description));
								echo "</td>";

							echo "</tr>\n";
							echo "</table>\n";

						echo "</td>\n";
					echo "</tr>\n";
					echo "<tr><td colspan=2>&nbsp;</td></tr>\n";
				}

				echo "</table>\n";

			} else {

				$f = new formInput;

				echo "<h2>Search</h2>\n";

				$f->start("$urlPrefix/pictures/search/submit", NULL, "GET");

				$f->text("q", NULL, 32);
				echo " &nbsp; ";
				$f->submit("Search");

				$f->end();
			}

			break;

		case "display":
			if (!$nocontent) break;

			$id = intval($arg1);

			$qry = "SELECT picture FROM {$dbTables['pictures']} WHERE id='$id'";

			if ($dbi->db->query($qry) && $dbi->db->num_rows()) {
				list($img) = $dbi->db->fetch_row();

				$qry = "UPDATE {$dbTables['pictures']} SET view_count = view_count + 1 WHERE id='$id'";
				$dbi->db->query($qry);

				header("Location: " . PICTURES_URL_PREFIX . "/" . $img);
			}
			break;

		default:
			$node = intval($_GET['node']);

			echo "<h2>Pictures</h2>\n";

			echo "<li><a href=\"$urlPrefix/pictures/search\">Search</a></li>\n";
			echo "<li><a href=\"$urlPrefix/pictures/slideshow\">Slideshow</a></li>\n";
			echo "<li><a href=\"$urlPrefix/pictures/random\">Random Picture</a></li>\n";
			echo "<br>\n";


?>
	<script language="javascript">
		function picture_jump(v) {
			document.location = '<?= "$urlPrefix/pictures/go/" ?>' + v;
		}
	</script>
<?

			echo "<div align=right>\n";

				echo "<form onSubmit=\"picture_jump(document.picture_jump_form.q.value); return false;\" name=\"picture_jump_form\">\n";
				echo "Go to folder &nbsp; ";
				echo "<input type=\"text\" name=\"q\" size=12> &nbsp; ";
				echo "<input type=\"button\" onClick=\"picture_jump(document.picture_jump_form.q.value);\" value=\"OK\">";
				echo "</form>\n";

			echo "</div>";

			echo "<br>\n";

         // path navbar
			$img_src = IMAGES_URL_PREFIX . "/folder.gif";
			echo "<table width=100% border=0 align=center>\n";
			echo "<tr>\n";
			echo "<td style=\"border-bottom: 1px solid black;\" colspan=3><img src=\"$img_src\">\n";

			$nav = new picture_navbar($dbi);
         $path = $nav->getPath($node);
         foreach($path as $id => $name) {
            echo "<A HREF=\"$urlPrefix/pictures?node=$id\"> $name</a> / ";
         }
         echo "<br>\n\n";

			echo "</td>\n";
			echo "</tr><tr>\n";
			// list children

         $children = $nav->getChildren($node);
			$count = count($children);

			if (!$count) {
				echo "<tr><td colspan=3 align=center><br><i>No folders found</i></td>\n";
			}

			$i = 0;
         foreach($children as $child => $name) {

				$folder_url = "$urlPrefix/pictures?node=$child";

				if ( ($i++ % 3) == 0) {
					echo "</tr><tr>\n";
				}
				echo "<td>\n";
					echo "<a href=\"$folder_url\"><img src=\"$img_src\" border=0> $name</a>\n";
				echo "</td>\n";
         }

			echo "</tr>\n";
			echo "</table>\n";
         echo "<br>\n";


			if ($node == 0) {
				echo "<h2>Top 5 Most Popular Pictures</h2>\n";
				$qry = "SELECT * FROM {$dbTables['pictures']} ORDER BY view_count DESC LIMIT 5";

			} else {
				echo "<h2>" . $nav->getName($node) . "</h2>\n";
				echo htmlspecialchars($nav->getDescription($node)) . "<br><br>\n";
				$qry = "SELECT * FROM {$dbTables['pictures']} WHERE category='$node' ORDER BY `name` ASC";
			}

			if (!$dbi->db->query($qry)) {
				echo "ERROR: Unable to query pictures<br>\n";
				break;
			}

			if (!$dbi->db->num_rows()) {
				echo "No pictures found to display<br>\n";
				break;
			}

         // display what's there / most popular
			echo "<table width=100% border=0 align=center cellspacing=0 cellpadding=0>\n";
			while ($pic = $dbi->db->fetch_object()) {

				$img_src = PICTURES_URL_PREFIX . "/" . $pic->thumbnail;
				$img_url = "$urlPrefix/pictures/display/$pic->id?nocontent=1";

				echo "<tr>";
					echo "<td><b>" . stripslashes($pic->name) . "</b></td>";
					echo "<td align=right>Seen $pic->view_count times</td>";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td style=\"border-top: 1px solid black;\" valign=top colspan=2>\n";

							echo "<table width=100% border=0 align=center cellspacing=0 cellpadding=2>\n";
							echo "<tr>\n";
								echo "<td valign=top width=1>";
									echo "<a target=_NEW href=\"$img_url\"><br><img style=\"border: 1px solid black;\" src=\"$img_src\" border=0></a>";
								echo "</td>\n";

								echo "<td valign=top><br>";
									echo "Added $pic->date_added<br>\n";
									echo "<br>\n";
									echo empty($pic->description) ? "<i>No description</i>" : htmlspecialchars(stripslashes($pic->description));

								echo "</td>";

							echo "</tr>\n";
							echo "</table>\n";

					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr><td colspan=2>&nbsp;</td></tr>\n";
			}

			echo "</table>\n";
			

		break;
	}
?>
