<?
	if (!defined("VALID")) die;
	switch ($func) {

		case "sclc":
			echo "<H2>Source code line count</H2>";

			if (file_exists("$localPath/source-totals.txt")) {
				$total = $col = $x = 0;
				$totals = file("$localPath/source-totals.txt");
				$c = count($totals);

				?>
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center" CELLSPACING=0>
				<TR>
					<TD>&nbsp;</TD>
					<TD><B>File</B></TD>
					<TD><B>Lines</B></TD>
				</TR>
				<?
				for($i=0; $i<$c; $i++) {
					$x++;
					$col = !$col;
					list($file, $count) = split(",", str_replace("\n", "", $totals[$i]));
					$cl = ($col) ? "row1" : "row2";
					$total += $count;
					if ($count) {
						echo "<TR CLASS=\"$cl\">";
							echo "<TD WIDTH=30>$x</TD>";
							echo "<TD>";
							echo (preg_match("/^\./", $file)) ? str_replace("./", "", $file) : basename($file);
							echo "</TD>";
							echo "<TD>$count</TD>";
						echo "</TR>\n";
					}
				}
				echo "<TR><TD COLSPAN=3>&nbsp;</TD></TR>\n";
				echo "<TR><TD COLSPAN=3 ALIGN=\"right\">" . formatNumber($total) . " lines later.. I wonder why. </TD></TR>\n";

				echo "</TABLE>";
			} else {
				echo "source-totals.txt doesn't exist!";
			}
			break;

		case "admin":
			?>
			<H2>Admin Source</H2>
			This is where you go to browse the source code for the admin backend. You can still browse the source even if you are
			not an administrator. It does obviously help to have access to what your looking at the source for =). Anyways, I havn't
			found a good way of laying out the source browser w/o writing too much code, but this will suffice for now.<BR><BR>

			Just thought I'd mention this, <B>.inc</B> files are included and used by other <B>.cat.php</B> files. They simply
			break up the file otherwise it'd be too big. The <B>.inc</B> files will come first (if any). Files have different
			extensions for security and organization.

			<H4>Soucre files in cat/admin/</H4>
			<?
			@$d = opendir("$localPath/cat/admin/");
			if ($d) {
				readdir($d);
				readdir($d);
				unset($files);
				while ($file = readdir($d))
					$files[] = $file;
				closedir($d);

				/* sort by extension ... I think there is a better way but i'm not sure ;) */
				array_walk($files, "reverseString"); sort($files); array_walk($files, "reverseString");

				$c = count($files);
				unset($l);
				$extNames = array('inc' => "Includes", 'cat.php' => "Category Files");
				for($i=0; $i<$c; $i++) {
					$file = $files[$i];
					$name = substr($file, 0, strrpos($file, "."));
					$ext = substr(strchr($file, "."), 1);
					$ext2 = substr(strrchr($file, "."), 1);
					if ($ext != $l)
						echo "<BR><B>" . ((isset($extNames[$ext])) ? "$extNames[$ext] (*.$ext)" : "*.$ext Files") . "</B><BR><BR>\n";

					echo "<LI><A HREF=\"$urlPrefix/source/backend/$ext2/$name\">$file</A></LI>\n";
					$l = $ext;
				}
			} else
				echo "<I>&nbsp; Couldn't browse directory!</I><BR>";
			break;

		case "backend":
			$file = str_replace("..", "", "$localPath/cat/admin/$arg2.$arg1");
			if (file_exists($file) && !empty($arg1) && !empty($arg2)) {
				?>
				<H2>Source code of '<?= "cat/admin/$arg2.$arg1" ?>'</H2>
				<A HREF="<?= "$urlPrefix/source" ?>">More about the Source...</A><BR>
				<?
					if ($nocontent)
						echo "<A HREF=\"" . $_SERVER['PHP_SELF'] . "\"><< Back</A>";
					else
						echo "<A HREF=\"" . $_SERVER['PHP_SELF'] . "?nocontent=1\">Full view...</A>";
				?>
				<OL><LI>
				<?
					ob_start();
					show_source($file);
					$t = ob_get_contents();
					ob_end_clean();
					echo ereg_replace( "<br />" , "</LI><LI>" , $t );
				echo "</LI></OL>\n";
			} else {
				$func = "admin";
				require("cat/$cat.cat.php");
			}
			break;

		case "main.php":
		case "misc":
		case "inc":
		case "cat":
		case "skin":
		case "class":
			if ($func == "inc" || $func == "cat") {
				$x = str_replace("..", "", $arg1);
				$ext = ($func == "inc") ? "" : ".cat.php";
				$sourcePath = $localPath;
				$sourceFile = "$func/$x" . $ext;

			} elseif ($func == "misc") {
				$allowedIncludes = array("home.php", "mvHandler.php");
				if (in_array($arg1, $allowedIncludes)) {
					$sourcePath = $_SERVER['DOCUMENT_ROOT'];
					$sourceFile = $arg1;
				}

			} elseif ($func == "class") {
				$x = str_replace("..", "", $arg1);
				$sourcePath = $localPath;
				$sourceFile = "class/$x";

			} elseif ($func == "main.php") {
				$sourcePath = $_SERVER['DOCUMENT_ROOT'] . "/homeflex";
				$sourceFile = "main.php";
				$arg1 = "main.php";

			} elseif ($func == "skin") {
				$x = str_replace("..", "", $arg1);
				$sourcePath = $_SERVER['DOCUMENT_ROOT'] . "/homeflex";
				$sourceFile = "themes/$x/index.html";

			} else {
				unset($sourceFile, $arg1);
			}

			if (file_exists("$sourcePath/$sourceFile") && !empty($arg1)) {
				?>
				<H2>Source code of '<?= $sourceFile ?>'</H2>
				<A HREF="<?= "$urlPrefix/source" ?>">More about the Source...</A><BR>
				<?
					if ($nocontent)
						echo "<A HREF=\"" . $_SERVER['PHP_SELF'] . "\"><< Back</A>";
					else
						echo "<A HREF=\"" . $_SERVER['PHP_SELF'] . "?nocontent=1\">Full view...</A>";
				?>

				<OL><LI>
				<?
					ob_start();
					@show_source("$sourcePath/$sourceFile");
					$t = ob_get_contents();
					ob_end_clean();
					echo ereg_replace( "<br />" , "</LI><LI>" , $t );
				echo "</LI></OL>\n";
			} else {
				unset($func);
				require("cat/$cat.cat.php");
			}
			
			break;

		case "sql":
			?>
			<H2>SQL Table structures</H2>
			<P>
			<H3>Database: <?= DB_DATABASE ?></H3>
			<?
			$tables = $dbi->db->list_tables(DB_DATABASE);
			while (list($table) = $dbi->db->fetch_row($tables)) {
				$dbi->db->query("SHOW CREATE TABLE `$table`");
				if ($dbi->db->num_rows()) {
					list(, $syntax) = $dbi->db->fetch_row();
					echo "<H4>$table</H4>";
					$sql = $dbi->db->query("SHOW TABLE STATUS LIKE '$table'");
					if ($sql && $dbi->db->num_rows()) {
						$tmp = $dbi->db->fetch_row();
						$comment = $tmp[14];
						if (!empty($comment)) echo "<I>" . htmlspecialchars($comment) . "</I><BR><BR>";
					} else
						echo $dbi->db->error();
					echo nl2br(htmlspecialchars($syntax));
					echo "<BR>";
				}
			}
			break;

		case "layout":
			?>
			<H2>Source Layout</H2>
			<P>
			Here I will attempt to describe how I layed out my code. I swear, there is some organization to it. Well, 
			atleast there was intent for organization. Anyways, I use Apache multiViews, if you don't know what they
			are go <A HREF="http://httpd.apache.org/docs/mod/core.html#options">and read up</A>.<P>
			The majority of the site code is in a folder not accessable by the web server. This fixes some
			security issues as well as makes a solid access point. Outside the directory, I have a single file (home.php)
			which just happens to reside in my apache root folder. To get around not having to have every page accessed
			like www.domain.com/home/projects or www.domain.com/home/contact, I wrote an additional file, mvHandler.php.
			mvHandler.php is very similar to home.php, but it's setup to you can make symbolic links for all the categories
			(pages your going to include into your layout). This allows you to have /projects or /contacts, for example.
			<P>
			That's basically it, This is a simple layout, that's nice, easy and quick to deploy.
			<P>
			<A HREF="<?= "$urlPrefix/source" ?>"><< Back</A>
			<?
			break;

		case "skins":
			echo "<H2>Skins</H2>";

			$fso = new fso();

			$files = $fso->loadDirectory("$localPath/themes");

			$dirs = $files[dir];
			sort($dirs);
			foreach($dirs as $dir) {
				if (!file_exists("$localPath/themes/$dir/index.html")) continue;
				echo "<LI><A HREF=\"$urlPrefix/source/skin/$dir\">$dir</A></LI>\n";
			}

			break;

		default:
			?>
			<H2>Source code</H2>
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<TR><TD>
				You can download all the source code for the homepage at the
				<A HREF="<?= "$urlPrefix/projects" ?>">Projects</A> page.<P>

				To view the source code of any page, click the "View Source" link at the bottom of the page.<P>

				If you see something included in a source file and you can't find the source for it, feel free to e-mail
				me and tell me and i'll put it online. I do believe, however, I got 'em all up online for your viewing
				pleasures. =)

				<P>

				<A HREF="<?= "$urlPrefix/source/layout" ?>">More Information >></A><BR>
				<A HREF="<?= "$urlPrefix/source/sql" ?>">SQL Structures >></A><BR>
				<A HREF="<?= "$urlPrefix/source/sclc" ?>">Source code line count >></A><BR>
				<A HREF="<?= "$urlPrefix/source/admin" ?>">Admin backend Source >></A><BR>
				<A HREF="<?= "$urlPrefix/source/skins" ?>">Skins >></A><BR>

				<H4>Other source files to take a look at</H4>

				<LI><A HREF="<?= "$urlPrefix/source/main.php" ?>">main.php</A></LI>
				<LI><A HREF="<?= "$urlPrefix/source/misc/home.php" ?>">home.php</A></LI>
				<LI><A HREF="<?= "$urlPrefix/source/misc/mvHandler.php" ?>">mvHandler.php</A></LI>
				<BR>
				<H3>Includes</H3>
				<?
					@$files = loadDirectory("$localPath/inc");
					sort($files);
					$c = count($files);
					for($i=0; $i<$c; $i++) {
						$file = $files[$i];
						echo "<LI><A HREF=\"$urLPrefix/source/inc/$file\">$file</A></LI>\n";
					}
				?>
				<BR>
				<H3>Classes</H3>
				<?
					@$files = loadDirectory("$localPath/class");
					sort($files);
					$c = count($files);
					for($i=0; $i<$c; $i++) {
						$file = $files[$i];
						echo "<LI><A HREF=\"$urLPrefix/source/class/$file\">$file</A></LI>\n";
					}
				?>
			</TD></TR>
			</TABLE>
			<?
			break;
	}
?>
