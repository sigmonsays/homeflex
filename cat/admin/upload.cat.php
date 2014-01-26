<?
	if (!defined("VALID")) die;

	switch ($arg1) {
		case "upload":
			?>
			<H2>Upload Files</H2>
			<?
	                        if (empty($arg2)) $arg2 = 3;
			?>
			<SCRIPT LANGUAGE="javascript">
				function setUploadCount(x) {
					document.location = '<?= "$urlPrefix/admin/upload/1/" ?>' + x;
				}
			</SCRIPT>
			<FORM NAME="upload" METHOD="post" ACTION="<?= "$urlPrefix/admin/upload/putSubmit" ?>" ENCTYPE="multipart/form-data">
			<INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="<?= MAX_FILE_SIZE ?>">
			<TABLE WIDTH="80%" BORDER=0 ALIGN="center" CELLSPACING=0>
			<TR BGCOLOR="#CCCCCC"><TD COLSPAN=2 ALIGN="center">
				Max Total Upload Size: <B><?= formatSize(MAX_FILE_SIZE) ?></B>
			</TD></TR>

			<TR BGCOLOR="#DDDDDD">
				<TD>Uploads</TD>
				<TD><INPUT TYPE="text" NAME="ulcount" SIZE=2 VALUE="<?= strip_tags($arg2) ?>"> &nbsp; <INPUT TYPE="button" OnClick="setUploadCount(document.upload.ulcount.value);" VALUE="Set"></TD>
			</TR>
			<?
				$c = 0;
				$x = 0;
				for($i=0; $i< $arg2; $i++) {
					$x++;
					$c = !$c;
					if ($c) $cl = "row1"; else $cl = "row2";
					echo "<TR CLASS=\"$cl\">";
						echo "<TD>File $x</TD>";
						echo "<TD><INPUT TYPE=\"file\" NAME=\"uploads[]\"></TD>";
					echo "</TR>\n";
				}

			?>
			<TR><TD COLSPAN=2 ALIGN="right"><INPUT TYPE="submit" VALUE="Submit"></TD></TR>
			</TABLE>
			</FORM>
			<?
			break;

		case "delete":
			$arg2 = str_replace("..", "", $arg2);
			@$r = unlink(UPLOAD_DIRECTORY . "/$arg2");
			if ($r) echo "Deleted $arg2<BR>"; else echo "Failed to delete $file<BR>";
			unset($arg1, $arg2);
			require("cat/$cat.cat.php");
			break;

		case "get":
			/*
				$arg2 = BS number (incremented)
				$arg3 = file to get =)
				Yes.. It's evil and should be done corectly, but it looks and seems secure, besides, I believe in backups
			*/
			if (headers_sent()) {
				?>
				<H2>Download <?= strip_tags($arg3) ?></H2>
				If your download doesn't automatically start, <A HREF="<?= "$urlPrefix/admin/upload/get/$arg2/" . strip_tags($arg3) . "/?nocontent=1" ?>">Click Here</A>.
				<?
					flush();
				?>
				<SCRIPT LANGUAGE="javascript">
					document.location = '<?= "$urlPrefix/upload/get/$arg2/" . strip_tags($arg3) . "/?nocontent=1" ?>';
				</SCRIPT>
				<?
			} else {
				$f = escapeshellarg(str_replace("..", "", $arg3));
				if (file_exists(UPLOAD_DIRECTORY . "/$arg3")) {
					$size = filesize(UPLOAD_DIRECTORY . "/$arg3");
					header("Content-length: $size");
					header("Content-type: applicaton/octet-stream");
					header("Content-Disposition: attachment; filename=$arg3;");
					passthru("cat '" . UPLOAD_DIRECTORY . "/$f'");
				} 
			}
			break;

		case "putSubmit":
			$c = count($uploads);
			$x = 0;
			for($i=0; $i<$c; $i++) {
				$x++;
				$file = $uploads[$i];
				$file_name = $uploads_name[$i];
				if ($file != '') {
					if (is_uploaded_file($file)) {
						$size = filesize($file);
						$dest = UPLOAD_DIRECTORY . "/" . time() . "_$file_name";
						if (move_uploaded_file($file, $dest)) {
							echo "File <B>$file_name</B> uploaded successfully<BR>";
						} else {
							echo "Failed to upload $x<BR>";
						}
					} else
						echo "Error accepting File $x<BR>";
				} else {
					echo "<B>File $x empty</B><BR>";
				}
			}
			break;

		default:
			?>
			<H2>Public Files</H2>
			<LI><A HREF="<?= "$urlPrefix/admin/upload/upload" ?>">Upload</A></LI>
			<?
				$dir = opendir(UPLOAD_DIRECTORY);
				if ($dir) {
					?>
					<TABLE WIDTH="80%" BORDER=0 ALIGN="center" CELLSPACING=0>
					<TR>
						<TD><B>File</B></TD>
						<TD><B>Size</B></TD>
						<TD><B>Date</B></TD>
						<TD>&nbsp;</TD>
					</TR>
					<?
					readdir($dir);
					readdir($dir);
					$c = $x = 0;
					while ($file = readdir($dir)) {
						if (file_exists(UPLOAD_DIRECTORY . "/$file") && filetype(UPLOAD_DIRECTORY . "/$file") == 'file') {
							$x++;
							$c = !$c;
							if ($c) $cl = "row1"; else $cl = "row2";
							echo "<TR CLASS=\"$cl\">";
								echo "<TD><A HREF=\"$urlPrefix/admin/upload/get/$x/$file?nocontent=1\">$file</A></TD>";
								echo "<TD>" . formatSize(filesize(UPLOAD_DIRECTORY . "/$file")) . "</TD>";
								echo "<TD>" . date("Y.m.d", filectime(UPLOAD_DIRECTORY . "/$file")) . "</TD>";
								echo "<TD ALIGN=\"center\">";
									echo "<A HREF=\"$urlPrefix/admin/upload/delete/$file\">delete</A>";
								echo "</TD>";
							echo "</TR>";
						}
					}
					if (!$x) echo "<TR><TD COLSPAN=4 ALIGN=\"center\">No Files!</TD></TR>";
					echo "</TABLE>";
				} else
					echo "No Files";
			break;
	}
?>
