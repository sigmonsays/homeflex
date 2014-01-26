<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['blog'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/blog";
	$form->uniqueField = "id";

	$form->setFieldType("when", "timestamp");
	$form->setFieldType("text", "textarea", 30, 70);
	$form->setFieldType("subject", "text", 50);

	$form->sqlOrderBy = "ORDER BY `when` DESC";

	$form->setFieldFunction("when", "timestamp", "F d Y");

	$fields = "subject,text";

	if ($form->formHandler("when,subject", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
