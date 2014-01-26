<?
	if (!defined("VALID")) die;

	switch ($arg1) {
		case "add":
			?>
			<H2>Add Group</H2>
			<FORM METHOD="post" ACTION="<?= $_SERVER['PHP_SELF'] ?>Submit">
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<TR>
				<TD>Group</TD>
				<TD><INPUT TYPE="text" NAME="fileGroup"> &nbsp; <INPUT TYPE="submit" VALUE="Submit"></TD>
			</TR>
			</TABLE>
			</FORM>
			<?
			break;

		case "addSubmit":
			$fileGroup = $_POST['fileGroup'];

			$sql = $dbi->db->query("INSERT INTO {$dbTables['files_cat']} VALUES(NULL, '$fileGroup')");
			unset($arg1);
			require("cat/$cat.cat.php");
			break;

		case "group":
			require("cat/admin/files-group.inc.php");
			break;

		case "edit":
			$sql = $dbi->db->query( "SELECT `name` FROM {$dbTables['files_cat']} WHERE id='$arg2'");
			if ($sql && $dbi->db->num_rows($sql)) {
				list($name) = $dbi->db->fetch_row($sql);
				?>
				<FORM METHOD="post" ACTION="<?= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], "/")) ?>Submit">
				<INPUT TYPE="hidden" NAME="id" VALUE="<?= $arg2 ?>">

				<TABLE WIDTH="50%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Category Name</TD>
					<TD><INPUT TYPE="text" NAME="categoryName" VALUE="<?= $name ?>"> &nbsp; <INPUT TYPE="submit" VALUE="Submit"></TD>
				</TR>
				</TABLE>
				</FORM>
				<?
			} else {
				unset($arg1);
				require("cat/$cat.cat.php");
			}
			break;

		case "editSubmit":
			$id = $_POST['id'];
			$categoryName = $_POST['categoryName'];

			$sql = $dbi->db->query( "UPDATE {$dbTables['files_cat']} SET name='$categoryName' WHERE id='$id'");

			unset($arg1);
			require("cat/$cat.cat.php");
			break;

		case "delete":
			$sql = $dbi->db->query( "SELECT `name` FROM {$dbTables['files_cat']} WHERE id='$arg2'");
			if ($sql && $dbi->num_rows($sql)) {
				list($name) = $dbi->fetch_row($sql);
					$sql = $dbi->db->query( "DELETE FROM {$dbTables['files_cat']} WHERE id='$arg2'");
					if (!$sql)
						echo "Failed to delete $name!<BR>";
			} else
				echo "Couldn't find File $arg2!<BR>";

			unset($arg1);
			require("cat/$cat.cat.php");
			break;

		case "upload":
			$noSubmit = 0;
			if (empty($arg2)) $arg2 = 3;
			$fileGroupID = intval($arg3);
/*
			$formAction = split("/", $_SERVER['PHP_SELF']);
			if (count($formAction) == 6)
				$formAction = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], "/"));
			else
				$formAction = $_SERVER['PHP_SELF'];
*/
			?>
			<H2>Upload Files</H2>
			<SCRIPT LANGUAGE="javascript">
				function setUploadCount(x) {
					document.location = '<?= "$urlPrefix/admin/files/upload/" ?>' + x + '/<?= $fileGroupID ?>';
				}
			</SCRIPT>
			<FORM NAME="upload" METHOD="post" ACTION="<?= "$urlPrefix/admin/files/uploadSubmit" ?>" ENCTYPE="multipart/form-data">
			<INPUT TYPE="hidden" NAME="nocontent" VALUE=1>
			<INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="<?= MAX_FILE_SIZE ?>">
			<TABLE WIDTH="60%" BORDER=0 ALIGN="center" CELLSPACING=0>
			<TR><TD COLSPAN=2><SMALL><?= formatSize(MAX_FILE_SIZE) ?> limit</SMALL></TD></TR>
			<TR BGCOLOR="#CCCCCC">
				<TD>File Group</TD>
				<TD><SELECT NAME="fileGroup">
				<?
					$sql = $dbi->db->query( "SELECT * FROM {$dbTables['files_cat']}");
					if ($sql && $dbi->db->num_rows($sql)) {
						while (list($id, $name) = $dbi->db->fetch_row($sql)) {
							if ($id == $fileGroupID) $sel = "SELECTED"; else $sel = "";
							echo "<OPTION $sel VALUE=\"$id\">$name</OPTION>\n";
						}
					} else {
						echo "<OPTION VALUE=\"-1\">--- None ---</OPTION>";
						$noSubmit = 1;
					}
				?>
				</SELECT></TD>
			</TR>

			<TR BGCOLOR="#DDDDDD">
				<TD>Uploads</TD>
				<TD><INPUT TYPE="text" NAME="ulcount" SIZE=2 VALUE="<?= $arg2 ?>"> &nbsp; <INPUT TYPE="button" OnClick="setUploadCount(document.upload.ulcount.value);" VALUE="Set"></TD>
			</TR>
			<?
				$c = 0;
				$x = 0;
				for($i=0; $i<$arg2; $i++) {
					$x++;
					$c = !$c;
					if ($c) $cl = "row1"; else $cl = "row2";
					echo "<TR CLASS=\"$cl\">";
						echo "<TD>File $x</TD>";
						echo "<TD><INPUT TYPE=\"file\" NAME=\"uploads[]\"></TD>";
					echo "</TR>\n";
				}

				echo "<TR><TD COLSPAN=2 ALIGN=\"center\">\n";
				if ($noSubmit)
					echo "<INPUT TYPE=\"button\" OnClick=\"alert('You must create a file group!');\" VALUE=\"Submit\">";
				else
					echo "<INPUT TYPE=\"submit\" VALUE=\"Submit\">";
				echo "</TD></TR>\n";

			?>
			</TABLE>
			</FORM>
			<?
			break;

		case "uploadSubmit":

			$fileGroup = $_POST['fileGroup'];

			$c = count($_FILES['uploads']);
			$x = 0;
			$uploaded = 0;
			for($i=0; $i<$c; $i++) {
				$x++;
				$file = $_FILES['uploads']['tmp_name'][$i];
				$file_name = $_FILES['uploads']['name'][$i];
				if ($file != '') {
					if (is_uploaded_file($file)) {
						$size = filesize($file);
						$dest = FILES_DIRECTORY . "/" . time() . "_$file_name";
						if (move_uploaded_file($file, $dest)) {
							$sql = $dbi->db->query(
								"INSERT INTO {$dbTables['files']} VALUES(NULL, '$file_name', '$size', '$fileGroup', "
								. "'$dest', '', '0', '0')");
							if ($sql) {
								$uploaded++;
								echo "File <B>$file_name</B> uploaded successfully<BR>";
							} else
								echo "File $file_name was moved but there was an SQL error!<BR>";
						} else {
							echo "Failed to upload $x<BR>";
						}
					} else
						echo "Error accepting File $x<BR>";
				} else {
					echo "<B>File $x empty</B><BR>";
				}
			}
			if ($uploaded == 0) {
				$arg1 = $fileGroup;
				require("cat/$cat.cat.php");
			}
			break;

		default:
			?>
			<H2>File Groups</H2>
			<A HREF="<?= "$urlPrefix/admin/files/upload" ?>">Upload</A> &nbsp; 
			<A HREF="<?= "$urlPrefix/admin/files/add" ?>">Add Group</A> &nbsp; 
			<P>
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center" CELLSPACING=0>
			<TR>
				<TD><B>Group</B></TD>
				<TD><B>Files</B></TD>
				<TD><B>Downloads</B></TD>
				<TD>&nbsp;</TD>
			</TR>
			<?
			$sql = $dbi->db->query( "SELECT * FROM {$dbTables['files_cat']} ORDER BY `name` ASC");
			if ($sql && $dbi->db->num_rows($sql)) {
				$c = 0;
				while (list($id, $name) = $dbi->db->fetch_row($sql)) {
					$sqlDownloads = $dbi->db->query(
						"SELECT SUM(downloads),COUNT(*) FROM {$dbTables['files']} WHERE filesid='$id'");
					if ($sqlDownloads) {
						list($sum, $fileCount) = $dbi->db->fetch_row($sqlDownloads);
						$sum = (int)$sum;
					}

					$c = !$c;
					if ($c) $cl = "row1"; else $cl = "row2";
					echo "<TR CLASS=\"$cl\">\n";
						echo "<TD><A HREF=\"$urlPrefix/admin/files/group/$id\">$name</A></TD>";
						echo "<TD>$fileCount</TD>";
						echo "<TD>$sum</TD>";
						echo "<TD ALIGN=\"center\">";
							echo "<A HREF=\"$urlPrefix/admin/files/edit/$id\">edit</A> &nbsp; ";
							echo "<A HREF=\"$urlPrefix/admin/files/delete/$id\">delete</A> &nbsp; ";
						echo "</TD>";
					echo "</TR>\n";
				}
			} else
				echo "<TR><TD COLSPAN=4>No Files!</TD></TR>\n";
			echo "</TABLE>";
			break;
	}
?>
