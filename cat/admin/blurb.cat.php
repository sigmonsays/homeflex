<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['blurb-box'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/blurb";
	$form->uniqueField = "id";

	$form->setFieldType("subject", "text", 40);
	$form->setFieldType("message", "textarea", 10, 40);
	$form->setFieldType("when", "date", "F d Y", "sql-timestamp");
	$form->sqlOrderBy = "ORDER BY `when` DESC";

	$form->title = "Blurb";

	if ($form->formHandler("when,subject", "when,subject,message", "when,subject,message")) {
			unset($_GET['action']);
			require("cat/$cat.cat.php");
	}
?>
