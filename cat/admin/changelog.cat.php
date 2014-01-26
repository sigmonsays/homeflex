<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['changelog'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/changelog";
	$form->uniqueField = "id";

	$form->setFieldType("subject", "text", 40);
	$form->setFieldType("text", "textarea", 10, 40);
	$form->setFieldType("when", "date", "F d Y", "sql-timestamp");

	$form->title = "Changelog";

	$form->sqlOrderBy = "ORDER BY `when` DESC";

	$fields = "when,subject,text";

	if ($form->formHandler("subject", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
