<?
	if (!defined("VALID")) die;


	$form = new formInput;

	switch ($arg1) {
		case "add":
			?>
			<H2>Add Section</H2>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/sections/addSubmit" ?>">
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<TR>
				<TD>Active</TD>
				<TD><? $form->boolean("sectionActive", 1) ?></TD>
			</TR>
				<TR>
					<TD>Privellage</TD>
					<TD><? $form->checkbox("sectionRegular", 1, 1) ?>Regular &nbsp; 
					    <? $form->checkbox("sectionAdmin", 1) ?>Admin</TD>
				</TR>
			<TR>
				<TD>Name</TD>
				<TD><? $form->text("sectionName") ?></TD>
			</TR>

			<TR>
				<TD><? $form->radio("sectionType", "include", 1) ?> Include</TD>
				<TD><? $form->text("sectionInclude") ?></TD>
			</TR>
			<TR>
				<TD><? $form->radio("sectionType", "url") ?> Url</TD>
				<TD><? $form->text("sectionUrl") ?></TD>
			</TR>
			<TR>
				<TD VALIGN="top">Description</TD>
				<TD><? $form->textarea("sectionDescription", 10, 50) ?></TD>
			</TR>

			<TR><TD COLSPAN=2>&nbsp;</TD></TR>
			<TR><TD COLSPAN=2 ALIGN="center"><? $form->submit() ?></TD></TR>
			</TABLE>
			</FORM>
			<?
			break;

		case "addSubmit":
			$sectionActive = $_POST['sectionActive'];
			$sectionRegular = $_POST['sectionRegular'];
			$sectionAdmin = $_POST['sectionAdmin'];
			$sectionName = $_POST['sectionName'];
			$sectionType = $_POST['sectionType'];
			$sectionInclude = $_POST['sectionInclude'];
			$sectionUrl = $_POST['sectionUrl'];
			$sectionDescription = $_POST['sectionDescription'];

			$sql = $dbi->db->query( 
				"INSERT INTO {$dbTables['sections']} VALUES(NULL, '$sectionName', '$sectionActive', '$sectionInclude', "
				. "'$sectionUrl', '$sectionDescription', '$sectionRegular', '$sectionAdmin', '', NULL, 1)");

			if (!$sql) echo "ERROR: " . $dbi->db->error();

			unset($arg1);
			require("cat/$cat.cat.php");
			break;

		case "edit":
			$id = intval($arg2);
			$sql = $dbi->db->query("SELECT * FROM {$dbTables['sections']} WHERE id='$id'");
			if ($sql && $dbi->db->num_rows($sql)) {
				$section = $dbi->db->fetch_object($sql);
				echo "$section->type";
				?>
				<H2>Edit Section</H2>
				<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/sections/editSubmit" ?>" ENCTYPE="multipart/form-data">
				<? $form->hidden("sectionId", $section->id); ?>
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Active</TD>
					<TD><? $form->boolean("sectionActive", $section->active) ?></TD>
				</TR>
				<TR>
					<TD>Privellage</TD>
					<TD><? $form->checkbox("sectionRegular", 1, $section->regular) ?>Regular &nbsp; 
					    <? $form->checkbox("sectionAdmin", 1, $section->admin) ?>Admin</TD>
				</TR>
				<TR>
					<TD>Name</TD>
					<TD><? $form->text("sectionName", $section->name) ?></TD>
				</TR>

				<TR>
					<TD><? $form->radio("sectionType", "include", !empty($section->include)) ?> Include</TD>
					<TD><? $form->text("sectionInclude", $section->include) ?></TD>
				</TR>
				<TR>
					<TD><? $form->radio("sectionType", "url", !empty($section->url)) ?> Url</TD>
					<TD><? $form->text("sectionUrl", $section->url) ?></TD>
				</TR>
				<TR>
					<TD VALIGN="top">Description</TD>
					<TD><? $form->textarea("sectionDescription", 10, 50, $section->description) ?></TD>
				</TR>

				<TR>
					<TD VALIGN="top">Logo</TD>
					<TD><? $form->file("sectionLogo") ?><BR>
					<?
						if (!empty($section->logo) && file_exists("$localPath/sectionLogos/$section->logo")) {

							$form->checkbox("sectionRemoveLogo", 1); echo "Remove this image<BR>";

							echo "<IMG STYLE=\"border: 1px solid black;\" SRC=\"$urlPrefix/homeflex/sectionLogos/$section->logo\"><BR>\n";
						} else {
							echo "<I>No Picture to Preview</I>\n";
						}
					?>
					</TD>
				</TR>

				<TR>
					<TD VALIGN="top">Show in Toolbar</TD>
					<TD><? $form->checkbox("sectionInToolbar", 1, $section->inToolbar); ?></TD>
				</TR>

				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR><TD COLSPAN=2 ALIGN="center"><? $form->submit("Update") ?></TD></TR>
				</TABLE>
				</FORM>
				<?
			} else {
				unset($arg1);
				require("cat/$cat.cat.php");
			}
			break;
	
		case "editSubmit":
			$sectionId = $_POST['sectionId'];
			$sectionActive = $_POST['sectionActive'];
			$sectionRegular = $_POST['sectionRegular'];
			$sectionAdmin = $_POST['sectionAdmin'];
			$sectionName = $_POST['sectionName'];
			$sectionType = $_POST['sectionType'];
			$sectionInclude = $_POST['sectionInclude'];
			$sectionUrl = $_POST['sectionUrl'];
			$sectionDescription = $_POST['sectionDescription'];
			$sectionLogo = $_FILES['sectionLogo'];
			$sectionRemovePicture = intval($_POST['sectionRemoveLogo']);
			$sectionInToolbar = intval($_POST['sectionInToolbar']);
			$sectionLogoName = -1;

			if (is_uploaded_file($sectionLogo['tmp_name']) && $sectionRemovePicture == 0) {
				$time = time();
				$ext = strrchr($sectionLogo['name'], ".");
				$sectionLogoName = $time . $ext;
				copy($sectionLogo['tmp_name'], "$localPath/sectionLogos/$sectionLogoName");
			}

			if ($sectionRemovePicture) {
				$sectionLogoName = "";
			}


			$sectionInclude = ereg_replace("\.cat\.php$", "", $sectionInclude);

			$qry = "UPDATE {$dbTables['sections']} SET active='$sectionActive', regular='$sectionRegular', admin='$sectionAdmin', name='$sectionName', include='$sectionInclude', "
				. "url='$sectionUrl', description='$sectionDescription', inToolbar='$sectionInToolbar' WHERE id='$sectionId'";

			$sql = $dbi->db->query($qry);
			if (!$sql) echo "<CENTER><FONT COLOR=\"red\">error updating {$dbTables['sections']}!</FONT></CENTER><BR>";

			if ($sectionLogoName != -1) {
				$qry = "UPDATE {$dbTables['sections']} SET logo='$sectionLogoName' WHERE id='$sectionId'";
				$sql = $dbi->db->query($qry);
				if (!$sql) echo "<CENTER><FONT COLOR=\"red\">error updating {$dbTables['sections']}!</FONT></CENTER><BR>";
			}

			unset($arg1);
			require("cat/$cat.cat.php");
			break;

		case "delete":
			$id = intval($arg2);
			$sql = $dbi->db->query("DELETE FROM {$dbTables['sections']} WHERE id='$id'");
			unset($arg1);
			require("cat/$cat.cat.php");
			break;
		
		default:
			?>
			<H2>Sections</H2>
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center" CELLSPACING=0>
			<TR><TD COLSPAN=3><A HREF="<?= "$urlPrefix/admin/sections/add" ?>">Add Section</A></TD></TR>
			<TR><TD COLSPAN=3>&nbsp;</TD></TR>
			<TR><TD COLSPAN=3><H3>Disabled</H3></TD></TR>
			<TR>
				<TD><B>Name</B></TD>
				<TD><B>Type</B></TD>
				<TD>&nbsp;</TD>
			</TR>
			<?
			$sql = $dbi->db->query("SELECT * FROM sections ORDER BY active ASC, name ASC");
			$active = 0;

			if ($sql && $dbi->db->num_rows($sql)) {
				$c = 0;
				while ($section = $dbi->db->fetch_object($sql)) {
					$class = (($color = !$color) ? "row1" : "row2");
					if ($section->active == 1 && !$active) {
						$active = 1;
						echo "<TR><TD COLSPAN=3>&nbsp;</TD></TR>";
						echo "<TR><TD COLSPAN=3><H3>Enabled</H3></TD></TR>\n";
					}

					echo "<TR>";
						echo ($section->active == 1) ? "<TD CLASS=\"$class\"><B>$section->name</B></TD>" : "<TD CLASS=\"$class\">$section->name</TD>";
						echo "<TD CLASS=\"$class\">";
							if ($section->regular == 1) {
								echo "Regular";
								if ($section->admin == 1) echo ", Admin";
							} else {
								if ($section->admin == 1) echo "Admin";
							}
						echo "</TD>";
						echo "<TD ALIGN=\"center\" CLASS=\"$class\">";
							echo "<A HREF=\"$urlPrefix/admin/sections/edit/$section->id\">edit</A> &nbsp; ";
							echo "<A HREF=\"$urlPrefix/admin/sections/delete/$section->id\">delete</A>";
						echo "</TD>";
					echo "</TR>\n";
				}

			}
			echo "</TABLE>";
			break;
	}
?>
