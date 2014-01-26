<?
	if (!defined("VALID")) die;

	switch ($arg2) {
		case "import":
			$sql = $dbi->db->query("SELECT * FROM {$dbTables['files_cat']} WHERE id='$arg3'");
			if ($sql && $dbi->db->num_rows($sql)) {
				list($id, $name) = $dbi->db->fetch_row($sql);
				$noSubmit = 0;
				?>
				<H2>Import Files Into '<?= $name ?>'</H2>
				<FORM METHOD="post" ACTION="<?= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], "/")) ?>Submit">
				<INPUT TYPE="hidden" NAME="id" VALUE="<?= $id ?>">

				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>File Group</TD>
					<TD><SELECT NAME="fileGroup">
					<?
						$sql = $dbi->db->query("SELECT * FROM {$dbTables['files_cat']}");
						if ($sql && $dbi->db->num_rows($sql)) {
							while (list($id, $name) = $dbi->db->fetch_row($sql)) {
								if ($id == $arg3) $sel = "SELECTED"; else $sel = "";
								echo "<OPTION $sel VALUE=\"$id\">$name</OPTION>\n";
							}
						} else {
							echo "<OPTION VALUE=\"-1\">--- None ---</OPTION>";
							$noSubmit = 1;
						}
					?>
					</SELECT></TD>
				</TR>

				<TR>
					<TD VALIGN="top">Files</TD>
					<TD>
						<TABLE WIDTH="100%" BORDER=0 ALIGN="center">
						<?
							$files = loadDirectory(FILES_DIRECTORY . "/archive");
							$c = count($files);
							if ($c) {
								for($i=0; $i<$c; $i++) {
									echo "<TR><TD>"
										. "<INPUT TYPE=\"checkbox\" NAME=\"files[]\" VALUE=\"{$files[$i]}\"> "
										. $files[$i] . "\n";
								}
							} else {
								echo "<TR><TD>No files in archive directory! <BR><SMALL>" . FILES_DIRECTORY . "/archive</SMALL></TD></TR>";
								$noSubmit = 1;
							}
						?>
						</TABLE>
					</TD>
				</TR>
				<?
					if (!$noSubmit)
						echo "<TR><TD COLSPAN=2 ALIGN=\"center\"><INPUT TYPE=\"Submit\" VALUE=\"Submit\"></TD></TR>";
				?>
				</TABLE>
				</FORM>
				<?
			} else {
				unset($arg2, $arg3);
				require("cat/$cat.cat.php");
			}
			break;

		case "importSubmit":
			$id = $_POST['id'];
			$fileGroup = $_POST['fileGroup'];
			$files = $_POST['files'];

			$c = count($files);
			for($i=0; $i<$c; $i++) {
				$f = $files[$i];
				$src = FILES_DIRECTORY . "/archive/$f";
				$dest = FILES_DIRECTORY . "/" . time() . "_$f";

				if (copy($src, $dest)) {

					if (unlink($src)) {

						@$size = filesize($dest);
						$sql = $dbi->db->query("INSERT INTO {$dbTables['files']} VALUES(NULL, '$f', '$size', '$fileGroup', '$dest', '', '0', '0')");
						if ($sql) {
							echo "Imported $f<BR>";
						} else {
							echo "Insert Error<BR>";
						}
					} else
						echo "Couldn't unlink the source!<BR>";

				} else
					echo "Failed to import $f!<BR>";

			}
			unset($arg2, $arg3);
			require("cat/$cat.cat.php");
			break;

		case "edit":
			$sql = $dbi->db->query("SELECT id,`name`,mimetype,filesid FROM {$dbTables['files']} WHERE id='$arg3'");

			if ($sql && $dbi->db->num_rows($sql)) {
				list($id, $file, $mimetype, $filesid) = $dbi->db->fetch_row($sql);
				?>
				<H2>Edit <?= $file ?></H2>
				<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/files/group/editSubmit/$id" ?>">
				<INPUT TYPE="hidden" NAME="filesid" VALUE="<?= $filesid ?>">
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Name</TD>
					<TD><INPUT TYPE="text" NAME="name" VALUE="<?= $file ?>"></TD>
				</TR>
				<TR>
					<TD>MIME Type</TD>
					<TD>
					<SELECT NAME="mimetype">
					<OPTION VALUE=-1>Automatic</OPTION>
					<OPTION VALUE=0>Default (application/ocet-stream)</OPTION>
					<?
						$sql = $dbi->db->query("SELECT * FROM {$dbTables['mimetypes']} ORDER BY `type`");
						while ($sql && $obj = $dbi->db->fetch_object($sql)) {
							$sel = (($mimetype == $obj->id) ? "SELECTED" : "");
							echo "<OPTION $sel VALUE=$obj->id>" . $obj->type . "</OPTION>\n";
						}
					?>
					</SELECT>
					</TD>
				</TR>
				<TR><TD COLSPAN=2 ALIGN="center"><BR><INPUT TYPE="Submit" VALUE="Update"></TD></TR>
				</TABLE>
				</FORM>
				<?
			} else {
				unset($arg2);
				require("cat/$cat.cat.php");
			}
			break;

		case "editSubmit":

			$name = $_POST['name'];
			$mimetype = $_POST['mimetype'];
			if (!empty($name) && !empty($mimetype)) {
				$sql = $dbi->db->query("UPDATE {$dbTables['files']} SET mimetype='$mimetype', name='$name' WHERE id='$arg3'");
			}

			$arg2 = $_POST['filesid'];
			require("cat/$cat.cat.php");
			break;

		case "delete":
			$sql = $dbi->db->query("SELECT `file`,filesid FROM {$dbTables['files']} WHERE id='$arg3'");
			if ($sql && $dbi->db->num_rows($sql)) {
				list($file, $filesid) = $dbi->db->fetch_row($sql);
				$dbi->db->query("DELETE FROM {$dbTables['files']} WHERE id='$arg3'");
				if (@!unlink($file)) {
					echo "Can't delete <B>$file</B>, Permission Denied or the file doesn't exist.<BR>";
				}
			}
			$arg2 = $filesid;
			require("cat/$cat.cat.php");

			break;

		default:
			$sql = $dbi->db->query("SELECT * FROM {$dbTables['files_cat']} WHERE id='$arg2'");
			if ($sql && $dbi->db->num_rows($sql)) {
				list($id, $name) = $dbi->db->fetch_row($sql);
				?>
				<H2>Edit File Group '<?= $name ?>'</H2>
				<A HREF="<?= "$urlPrefix/admin/files/group/import/$id" ?>">Import</A> &nbsp; 
				<A HREF="<?= "$urlPrefix/admin/files/upload/3/$id" ?>">Upload</A> &nbsp; 
				<P>
				<FORM METHOD="post" ACTION="<?= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], "/")) ?>Submit">
				<INPUT TYPE="hidden" NAME="id" VALUE="<?= $arg3 ?>">

				<TABLE WIDTH="90%" BORDER=0 ALIGN="center" CELLSPACING=0>
				<TR>
					<TD><B>File</B></TD>
					<TD><B>Downloads</B></TD>
					<TD><B>Size</B></TD>
					<TD>&nbsp;</TD>
				</TR>
				<?
					$sql = $dbi->db->query("SELECT * FROM {$dbTables['files']} WHERE filesid='$arg2'");
					if ($sql && $dbi->db->num_rows($sql)) {
						$c = 0;
						$x = 0;
						while ($file = $dbi->db->fetch_object($sql)) {
							$c = !$c;
							$x++;
							if ($c) $cl = "row1"; else $cl = "row2";
							echo "<TR CLASS=\"$cl\">";
								echo "<TD>$file->name</TD>";
								echo "<TD>$file->downloads</TD>";
								echo "<TD>" . formatSize($file->size) . "</TD>";
								echo "<TD ALIGN=\"center\">";
									echo "<A HREF=\"$urlPrefix/admin/files/group/edit/$file->id\">edit</A> &nbsp; ";
									echo "<A HREF=\"$urlPrefix/admin/files/group/delete/$file->id\">delete</A>\n";
								echo "</TD>";
							echo "</TR>\n";
						}
					} else
						echo "<TR><TD COLSPAN=3>No Files in this group</TD></TR>";
				?>
				</TABLE>
				</FORM>
				<?
			} else {
				unset($arg1, $arg2);
				require("cat/$cat.cat.php");
			}
			break;

	}
?>
