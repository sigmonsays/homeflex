<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['countdown'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/countdown";
	$form->uniqueField = "id";

	$form->setFieldType("message", "textarea", 10, 40);
	$form->setFieldType("date", "date", "F d Y - h : i", "sql-timestamp");
	$form->setFieldFunction("date", "timestamp", "F d Y");

	$form->sqlOrderBy = "ORDER BY `date` DESC";

	$fields = "date,subject,message,link";

	if ($form->formHandler("subject,date", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
