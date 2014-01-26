<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['users'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/users";
	$form->uniqueField = "id";

	$form->setFieldType("user", "text", 32);
	$form->setFieldType("password", "password", "mysql", TRUE);
	$form->setFieldType("created", "date", "F d Y", "sql-timestamp");
	$form->setFieldType("active", "boolean", "Enabled/Disabled");
	$form->setFieldType("email", "text", 32);
	$form->setFieldType("website", "text", 32);

	$form->setFieldType("level", "select", $form->selectSource("level", $dbTables['levels'], "id", "name"));

	$form->title = "Users";

	$form->sqlOrderBy = "ORDER BY `created` DESC";

	$fields = "active,user,password,email,website,aim,level";

	if ($form->formHandler("user,email,level,active", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
