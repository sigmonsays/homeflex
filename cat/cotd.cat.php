<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "past":

				$form = new sqlForm($dbi, $dbTables['cotd'], DB_DATABASE);
				$form->urlPrefix = "$urlPrefix/cotd/past";
				$form->uniqueField = "day";

				$form->setFieldType("command", "textarea", 10, 40);
				$form->setFieldType("description", "textarea", 10, 40);
				$form->sqlOrderBy = "ORDER BY `day` ASC";
				$form->mode = "list,search";
				$form->title = "Command of the day";

				$form->formHandler("day,description,command");
			
			break;

		case "submit":
			if (empty($_POST['command']) || empty($_POST['description'])) {
				unset($func);
				require("$localPath/cat/$cat.cat.php");
			} else {

				$d = date("z");
				$sql = $dbi->db->query("INSERT INTO {$dbTables['cotd']} VALUES('$d', '" . $_POST['command'] . "', '" . $_POST['description'] . "')");

				if ($sql) {
					echo "<CENTER>Thank you for submitting the Linux command of the day</CENTER><BR>";
				}
				unset($func);
				require("cat/$cat.cat.php");
			}
			break;

		default:
			$d = date("z");
			$sql = $dbi->db->query("SELECT * FROM {$dbTables['cotd']} WHERE day='$d'");
			if ($sql && $dbi->db->num_rows($sql)) {
				$cotd = $dbi->db->fetch_object($sql);
				?>
				<H2>Linux Command of the Day</H2>
				<A HREF="<?= "$urlPrefix/cotd/past" ?>">Past commands....</A><BR><BR>

				<B>Description</B><BR>
				<?= htmlspecialchars($cotd->description); ?>
				<P>
				<B>Command</B><BR><PRE><?
				echo htmlspecialchars($cotd->command) . "</PRE>\n";
			} else {
				$form = new formInput;
				?>
				<H2>Submit the Linux Command of the Day</H2>
				<FORM METHOD="post" ACTION="<?= "$urlPrefix/cotd/submit" ?>">
				There isn't a command of the day for today!<P>
				You can submit one if you'd like!
				<P>
				<?
				echo "<B>Description</B><BR>";
				$form->textarea("description", 10, 50);
				echo "<P>";

				echo "<B>Command</B><BR>";
				$form->textarea("command", 10, 50);
				echo "<P>";
				$form->submit("Submit Command");
				?>
				<BR><BR>
				<A HREF="<?= "$urlPrefix/cotd/past" ?>">Past commands....</A><BR><BR>
				</FORM>
				<?
			}
			break;

	}
?>
