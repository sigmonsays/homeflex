<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['todo'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/todo";
	$form->uniqueField = "id";
	$form->sqlStart = $start;
	$form->sqlOrderBy = "ORDER BY complete ASC, `when` DESC";

	$form->setFieldType("complete", "boolean");
	$form->setFieldType("text", "textarea", 10, 50);
	$form->setFieldType("when", "date", "F d Y", "sql-timestamp");

	$form->setFieldFunction("complete", "boolean", "built-in", "Yes/No");

	$fields = "when,subject,text,complete";

	if ($form->formHandler("subject,complete", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
