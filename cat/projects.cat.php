<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "download":
			if (headers_sent()) {
				/*
					select projects.projects.name as projectName 
					from files.files,projects.projects 
					where projects.projects.filesid = files.files.filesid and files.files.id = '4';
				*/

				$sqlProjectName = $dbi->db->query(
					"SELECT {$dbTables['projects']}.name, {$dbTables['projects']}.filesid as projectName "
					. "FROM {$dbTables['files']}, {$dbTables['projects']} "
					. "WHERE {$dbTables['projects']}.filesid = {$dbTables['files']}.filesid "
					. "AND {$dbTables['files']}.id = '$arg1'");
				if ($sqlProjectName && $dbi->db->num_rows($sqlProjectName))
					list($projectName, $fileGroupID) = $dbi->db->fetch_row($sqlProjectName);
				
				$sqlProject = $dbi->db->query(
					"SELECT * FROM {$dbTables['files']} WHERE filesid='$fileGroupID'");
				if ($sqlProject && $dbi->db->num_rows($sqlProject)) {
					$project = $dbi->db->fetch_object($sqlProject);
					?>
					<H2>Projects :: Downloads :: <?= $projectName ?></H2>
					<P>
					If your download doesn't automatically start, <A HREF="<?= "$urlPrefix/projects/download/$arg1/$project->name?nocontent=1" ?>">Click Here</A>.
					<SCRIPT LANGUAGE="javascript">
						document.location = '<?= "$urlPrefix/projects/download/$arg1/$project->name?nocontent=1" ?>';
					</SCRIPT>
					<?
				} else {
					unset($func);
					require("cat/$cat.cat.php");
				}
			} else {
				$sql = $dbi->db->query(
					"SELECT file,name,size,mimetype FROM {$dbTables['files']} WHERE id='$arg1'");
				if ($sql && $dbi->db->num_rows($sql)) {

					$sqlDownload = $dbi->db->query(
						"UPDATE {$dbTables['files']} SET downloads=downloads+1 WHERE id='$arg1'");

					

					list($file, $name, $size, $mimetype) = $dbi->db->fetch_row($sql);

					if ($mimetype == -1) { /* automatic mode */
						$ext = strrchr($name, ".");
						$sql = $dbi->db->query("SELECT `type` FROM {$dbTables['mimetypes']} WHERE extension='$ext'");
						if ($sql && $dbi->db->num_rows($sql)) {
							list($mtype) = $dbi->db->fetch_row($sql);
							header("Content-length: $size");
							header("Content-type: $mtype");
							passthru("cat '$file'");
						}


					} else if ($mimetype == 0) {
						header("Content-length: $size");
						header("Content-type: applicaton/octet-stream");
						header("Content-Disposition: attachment; filename=$name;");
						passthru("cat '$file'");

					} else { /* attempt to lookup mimetype */
						$sql = $dbi->db->query("SELECT `type` FROM {$dbTables['mimetypes']} WHERE id='$mimetype'");
						if ($sql && $dbi->db->num_rows($sql)) {
							list($mtype) = $dbi->db->fetch_row($sql);
							header("Content-length: $size");
							header("Content-type: $mtype");
							passthru("cat '$file'");
						}
					}
				} else {
					unset($func);
					require("cat/$cat.cat.php");
				}
			}
			break;

		case "name":
			$sql = $dbi->db->query(
				"SELECT id FROM {$dbTables['projects']} WHERE name='$arg1'");
			if ($sql && $dbi->db->num_rows($sql))
				list($arg1) = $dbi->db->fetch_row($sql);
			else
				$arg1 = 0;

		case "id":
			$sql = $dbi->db->query(
				"SELECT {$dbTables['projects']}.*, {$dbTables['projects_cat']}.name as catName "
				. "FROM {$dbTables['projects']}, {$dbTables['projects_cat']} "
				. "WHERE {$dbTables['projects']}.categoryID = {$dbTables['projects_cat']}.id "
					. "AND {$dbTables['projects']}.id='$arg1'");

			echo $dbi->db->error();

			if ($sql && $dbi->db->num_rows($sql)) {
				$project = $dbi->db->fetch_object($sql);


				$sqlView = $dbi->db->query(
					"UPDATE {$dbTables['projects']} SET views=views+1 WHERE id='$arg1'");

				echo "<H2>Projects :: $project->catName :: $project->name</H2>\n";
				?>
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR><TD>Added: <?= formatTime($project->when) ?></TD></TR>
				<TR><TD><?= stripslashes($project->description) ?></TD></TR>
				<TR><TD>
					<BR>
					<B><FONT SIZE="+1">Files</FONT></B><BR>
					<?
						$sqlDownloads = $dbi->db->query(
							"SELECT * FROM {$dbTables['files']} WHERE filesid='$project->filesid'");
						if (!$dbi->db->num_rows($sqlDownloads)) echo " &nbsp; <I>No files</I><BR>";
						while ($sqlDownloads && $file = $dbi->db->fetch_object($sqlDownloads))
							echo "<A HREF=\"$urlPrefix/projects/download/$file->id/$file->name?nocontent=1\">$file->name</A> (" . formatSize($file->size) . ") &nbsp; " . stripslashes($file->description) . "<BR>\n";
					?>

				</TD></TR>
				</TABLE>
				<?
			} else {
				unset($func);
				require("cat/$cat.cat.php");
			}
			
			break;

		default:
			$id = $_POST['id'];

			if (!isset($id)) $id = isset($func) ? intval($func): -2;

			echo '<H2>Projects</H2>';

			if ($id == -2) {
				$sql = $dbi->db->query("SELECT id,name,description,categoryID FROM {$dbTables['projects']} ORDER BY id DESC LIMIT 0, 1");
				if ($sql && $dbi->db->num_rows($sql)) {
					$newest = $dbi->db->fetch_object($sql);

					echo "Newest project is <A HREF=\"$urlPrefix/projects/id/$newest->id\">$newest->name</A> in ";

					$sql = $dbi->db->query("SELECT id,name FROM {$dbTables['projects_cat']} WHERE id='$newest->categoryID'");

					if ($sql && $dbi->db->num_rows($sql)) {
						$projectCategory = $dbi->db->fetch_object($sql);
						echo "<A HREF=\"$urlPrefix/projects/$projectCategory->id\">$projectCategory->name</A>.";
					} else {
						echo '<FONT COLOR="red">Unknown</FONT>.';
					}

					echo "<BR><SMALL>";
					if (strlen($newest->description) > 128) {
						echo stripslashes(substr($newest->description, 0, 128));
						echo "...</SMALL> &nbsp; <A HREF=\"$urlPrefix/projects/id/$newest->id\">[more]</A>";
					} else
						echo stripslashes($newest->description);
					echo "<P>";
				}

				echo $dbi->db->error();
			}

			?>
			<CENTER>
			<FORM NAME="projects" METHOD="post" ACTION="<?= "$urlPrefix/projects" ?>">
				<SELECT NAME="id" OnChange="document.projects.submit();">
				<OPTION VALUE="-1">&nbsp Select Category....</OPTION>

				<? if ($id == 0) $sel = "SELECTED"; else $sel = ""; ?>
				<OPTION <?= $sel ?> VALUE="0">&nbsp; [ All Projects ]</OPTION>
				<?
					$sql = $dbi->db->query(
						"SELECT * FROM {$dbTables['projects_cat']} ORDER BY weight DESC, name ASC");
					while ($sql && list($i, $name) = $dbi->db->fetch_row($sql)) {
						if ($id == -2) $id = $i;
						if ($i == $id) $sel = "SELECTED"; else $sel = "";
						echo "<OPTION $sel VALUE=\"$i\">$name</OPTION>\n";
					}
				?>
				</SELECT> &nbsp; <INPUT TYPE="submit" VALUE="OK">
			</FORM>
			</CENTER>
			<?
			if ($id == 0)
				$where = "";
			elseif ($id == -1)
				$where = "";
			else
				$where = "WHERE id='$id'";

			if ($id != -1) {
				$sql = $dbi->db->query("SELECT * FROM {$dbTables['projects_cat']} $where");
				while ($sql && list($i, $name) = $dbi->db->fetch_row($sql)) {
					echo "<H4>$name</H4>\n";
					echo "<UL>\n";
					$sqlProjects = $dbi->db->query(
						"SELECT id,name,description,`when` FROM {$dbTables['projects']} WHERE categoryID='$i'");
					while ($sqlProjects && $project = $dbi->db->fetch_object($sqlProjects)) {
						echo "<LI><A HREF=\"$urlPrefix/projects/id/$project->id\">$project->name</A><BR>"
							. formatTime($project->when) . "<P>" . stripslashes($project->description) . "</LI><BR>\n";
					}
					echo "</UL>\n";
				}
			} else {
				echo "<A HREF=\"$urlPrefix/projects/all\">Click here</A> to display all projects</A>.";
			}
			break;
	}
?>
