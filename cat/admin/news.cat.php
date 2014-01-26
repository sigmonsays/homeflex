<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['news'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/news";
	$form->uniqueField = "id";
	$form->sqlStart = $start;
	$form->sqlOrderBy = "ORDER BY `when` DESC";

	$form->setFieldType("subject", "text", 40);
	$form->setFieldType("post", "textarea", 10, 50);
	$form->setFieldType("when", "date", "F d Y", "sql-timestamp");

	$form->setFieldFunction("when", "timestamp", "F d Y");

	$form->mode = "add,edit,delete,list,search,view";

	$fields = "when,subject,post,user";

	if ($form->formHandler("user,when,subject", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
