<?
	if (!defined("VALID")) die;

	switch ($arg2) {
		case "add":
			?>
			<H2>Add Category</H2>
			<FORM METHOD="post" ACTION="<?= $_SERVER['PHP_SELF'] ?>Submit">

			<TABLE WIDTH="50%" BORDER=0 ALIGN="center">
			<TR>
				<TD>Name</TD>
				<TD><INPUT TYPE="text" NAME="categoryName"></TD>
			<TR>

			<TR>
				<TD>Weight</TD>
				<TD><INPUT TYPE="text" NAME="categoryWeight" SIZE=2></TD>
			</TR>

			<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Submit"></TD></TR>

			</TABLE>
			</FORM>
			<?
			break;

		case "addSubmit":
			$categoryName = $_POST['categoryName'];
			$categoryWeight = $_POST['categoryWeight'];

			$categoryName = $dbi->db->escape_string(strip_tags($categoryName));
			$sql = $dbi->db->query("INSERT INTO {$dbTables['projects_cat']} VALUES(NULL, '$categoryName', '$categoryWeight')");
			unset($arg2);
			require("cat/$cat.cat.php");
			break;

		case "delete":
			/* I really should check if there is projects in the category before deleting it, perhaps move 'em to a (unfiled) category
			   or something.. maybe in due time.
			*/
			$sql = $dbi->db->query("DELETE FROM {$dbTables['projects_cat']} WHERE id='$arg3'");
			unset($arg2);
			require("cat/$cat.cat.php");
			break;

		case "edit":

			$sql = $dbi->db->query(
				"SELECT * FROM {$dbTables['projects_cat']} WHERE id='$arg3'");
			if ($sql && $dbi->db->num_rows($sql)) {
				$category = $dbi->db->fetch_object($sql);
				?>
				<H2>Edit Category</H2>
				<FORM METHOD="post" ACTION="<?= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], "/")) ?>Submit">
				<INPUT TYPE="hidden" NAME="id" VALUE="<?= $category->id ?>">
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Category</TD>
					<TD><INPUT TYPE="text" NAME="categoryName" VALUE="<?= $category->name ?>"></TD>
				</TR>

				<TR>
					<TD>Weight</TD>
					<TD><INPUT TYPE="text" NAME="categoryWeight" VALUE="<?= $category->weight ?>" SIZE=2></TD>
				</TR>

				<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Submit"></TD></TR>
				</TABLE>
				</FORM>
				<?
			} else {
				unset($arg2);
				require("cat/$cat.cat.php");
			}
			break;

		case "editSubmit":
			$id = $_POST['id'];
			$categoryName = $_POST['categoryName'];
			$categoryWeight = $_POST['categoryWeight'];

			$categoryName = $dbi->db->escape_string(strip_tags($categoryName));
			$sql = $dbi->db->query("UPDATE {$dbTables['projects_cat']} SET name='$categoryName',weight='$categoryWeight' WHERE id='$id'");
			unset($arg2);
			require("cat/$cat.cat.php");
			break;

		default:
			?>
			<H2>Categories</H2>
			<A HREF="<?= "$urlPrefix/admin/projects/categories/add" ?>">Add Category</A> &nbsp; 
			<P>
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center" CELLSPACING=0>
			<TR>
				<TD><B>Category</B></TD>
				<TD><B>Projects</B></TD>
				<TD><B>Weight</B></TD>
				<TD>&nbsp;</TD>
			</TR>
			<?

			$sql = $dbi->db->query("SELECT * FROM {$dbTables['projects_cat']} ORDER BY weight DESC");

			$c = 0;
			while ($sql && list($id,$name, $weight) = $dbi->db->fetch_row($sql)) {
				$sqlProjectCount = $dbi->db->query("SELECT COUNT(*) FROM {$dbTables['projects']} WHERE categoryID='$id'");
				if ($sqlProjectCount && $dbi->db->num_rows($sqlProjectCount))
					list($total) = $dbi->db->fetch_row($sqlProjectCount);
				else
					$total = 0;

				$c = !$c;
				if ($c) $cl = "row1"; else $cl = "row2";

				echo "<TR CLASS=\"$cl\">";
					echo "<TD>$name</TD>";
					echo "<TD>$total</TD>";
					echo "<TD>$weight</TD>";
					echo "<TD>";
						echo "<A HREF=\"$urlPrefix/admin/projects/categories/delete/$id\">delete</A> &nbsp; ";
						echo "<A HREF=\"$urlPrefix/admin/projects/categories/edit/$id\">edit</A>";
					echo "</TD>";
				echo "</TR>\n";
			}
			echo "</TABLE>";
			break;

	}
?>
