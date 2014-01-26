<?
	if (!defined("VALID")) die;

	switch ($arg1) {
		case "categories":
			require("cat/admin/projects-categories.inc.php");
			break;

		case "delete":
			$dbi->db->query("DELETE FROM {$dbTables['projects']} WHERE id='$arg2'");
			unset($arg1);
			require("cat/$cat.cat.php");
			break;

		case "edit":
			$dbi->db->query("SELECT * FROM {$dbTables['projects']} WHERE id='$arg2'");
			if ($dbi->db->num_rows()) {
				$project = $dbi->db->fetch_object();
				?>
				<H2>Edit Project</H2>
				<FORM METHOD="post" ACTION="<?= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], "/")) ?>Submit">
				<INPUT TYPE="hidden" NAME="id" VALUE="<?= $arg2 ?>">
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Category</TD>
					<TD><SELECT NAME="projectCategory">
					<?
						$dbi->db->query("SELECT * FROM {$dbTables['projects_cat']}");
						while ($cat = $dbi->db->fetch_object()) {
							if ($cat->id == $project->categoryID) $sel = "SELECTED"; else $sel = "";
							echo "<OPTION $sel VALUE=\"$cat->id\">$cat->name</OPTION>\n";
						}
					?>
					</SELECT></TD>
				</TR>

				<TR>
					<TD>Name</TD>
					<TD><INPUT TYPE="text" NAME="projectName" SIZE=30 VALUE="<?= $project->name ?>"></TD>
				</TR>

				<TR>
					<TD>File Group</TD>
					<TD><SELECT NAME="fileGroup">
					<?
						$dbi->db->query("SELECT * FROM {$dbTables['files_cat']}");
						while ($cat = $dbi->db->fetch_object()) {
							if ($cat->id == $project->filesid) $sel = "SELECTED"; else $sel = "";
							echo "<OPTION $sel VALUE=\"$cat->id\">$cat->name</OPTION>\n";
						}
					?>
					</SELECT></TD>
				</TR>

				<TR>
					<TD>Description</TD>
					<TD><TEXTAREA NAME="projectDescription" ROWS=10 COLS=60><?= stripslashes($project->description) ?></TEXTAREA></TD>
				</TR>

				<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Submit"></TD></TR>
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
			$projectCategory = $_POST['projectCategory'];
			$projectName = $_POST['projectName'];
			$projectDescription = $_POST['projectDescription'];
			$fileGroup = $_POST['fileGroup'];

			$projectDescription = $dbi->db->escape_string(strip_tags($projectDescription));

			$dbi->db->query(
				"UPDATE {$dbTables['projects']} SET categoryID='$projectCategory', "
				. "name='$projectName', description='$projectDescription', filesid='$fileGroup' "
				. "WHERE id='$id'");
			unset($arg1);
			require("cat/$cat.cat.php");
			break;

		case "add":
			?>
			<H2>Add Project</H2>
			<FORM METHOD="post" ACTION="<?= $_SERVER['PHP_SELF'] ?>Submit">
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<TR>
				<TD>Category</TD>
				<TD><SELECT NAME="projectCategory">
				<?
					$dbi->db->query("SELECT * FROM {$dbTables['projects_cat']} ORDER BY name ASC");
					while ($cat = $dbi->db->fetch_object())
						echo "<OPTION VALUE=\"$cat->id\">$cat->name</OPTION>\n";
				?>
				</SELECT></TD>
			</TR>

			<TR>
				<TD>Name</TD>
				<TD><INPUT TYPE="text" NAME="projectName" SIZE=30></TD>
			</TR>

			<TR>
				<TD>File Group</TD>
				<TD><SELECT NAME="fileGroup">
				<?
					$dbi->db->query("SELECT * FROM {$dbTables['files_cat']} ORDER BY name ASC");
					while ($cat = $dbi->db->fetch_object())
						echo "<OPTION VALUE=\"$cat->id\">$cat->name</OPTION>\n";
				?>
				</SELECT></TD>
			</TR>

			<TR>
				<TD>Description</TD>
				<TD><TEXTAREA NAME="projectDescription" ROWS=10 COLS=60></TEXTAREA></TD>
			</TR>

			<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Submit"></TD></TR>
			</TABLE>
			</FORM>
			<?
			break;

		case "addSubmit":
			$id = $_POST['id'];
			$projectCategory = $_POST['projectCategory'];
			$projectName = $_POST['projectName'];
			$projectDescription = $_POST['projectDescription'];
			$fileGroup = $_POST['fileGroup'];

			$projectName = $dbi->db->escape_string($projectName);
			$projectDescription = $dbi->db->escape_string($projectDescription);

			$sql = $dbi->db->query("INSERT INTO {$dbTables['projects']} "
				. "VALUES(NULL, NULL, '$projectCategory', '$projectName', '$projectDescription', '$fileGroup', '0')");

			if ($sql)
				echo "Added project $projectName<BR>";
			else
				echo "Error adding project $projectName!<BR>";

			unset($arg1);
			require("cat/$cat.cat.php");
			break;


		default:
			?>
			<H2>Projects</H2>
			<A HREF="<?= "$urlPrefix/admin/projects/add" ?>">Add Project</A> &nbsp; 
			<A HREF="<?= "$urlPrefix/admin/projects/categories" ?>">Categories</A> &nbsp; 
			<P>
			<?
			$sql = $dbi->db->query("SELECT * FROM {$dbTables['projects_cat']} ORDER BY weight DESC");
			while (list($id, $name) = $dbi->db->fetch_row($sql)) {
				echo "<H4>$name</H4>\n";

				$sqlProjects = $dbi->db->query(
					"SELECT id,name,views,filesid FROM {$dbTables['projects']} WHERE categoryID='$id' ORDER BY `name` ASC");
				if ($dbi->db->num_rows($sqlProjects)) {
					?>
					<TABLE WIDTH="90%" BORDER=0 ALIGN="center" CELLSPACING=0>
					<TR>
						<TD WIDTH=30>&nbsp;</TD>
						<TD><B>Project</B></TD>
						<TD><B>Viewed</B></TD>
						<TD><B>Downloads / Files</B></TD>
						<TD>&nbsp;</TD>
					</TR>
					<?

					$c = $i = 0;
					while ($project = $dbi->db->fetch_object($sqlProjects)) {
						$i++;
						$c = !$c;
						$col = ($c) ? "row1" : "row2";


						$sqlDL = $dbi->db->query("SELECT COUNT(*), SUM(downloads) FROM {$dbTables['files']} "
									. "WHERE filesid='$project->filesid'");
						if ($sqlDL) {
							list($dlCount, $dlSum) = $dbi->db->fetch_row($sqlDL);
							$dlCount = (int)$dlCount;
							$dlSum = (int)$dlSum;
							@$div = (int)($dlSum / $dlCount);
						} else {
							$dlCount = $dlSum = -1;
						}

						echo "<TR CLASS=\"$col\">";

							echo "<TD WIDTH=30>$i</TD>";
							echo "<TD>$project->name</TD>";
							echo "<TD>$project->views</TD>";
							echo "<TD>$dlSum / $dlCount = $div</TD>";
							echo "<TD ALIGN=\"center\">";
								echo "<A HREF=\"$urlPrefix/admin/projects/edit/$project->id\">edit</A> &nbsp; ";
								echo "<A HREF=\"$urlPrefix/admin/projects/delete/$project->id\">delete</A>";
							echo "</TD>";
						echo "</TR>\n";
					}
					echo "</TABLE>\n";
				}
			}
			break;
	}
?>
