<?
	if (!defined("VALID")) die;


	$form = new sqlForm($dbi, $dbTables['cotd'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/cotd";
	$form->uniqueField = "day";

	$form->setFieldType("command", "textarea", 10, 40);
	$form->setFieldType("description", "textarea", 10, 40);
	$form->setFieldType("day", "text", 3);

	$form->title = "Command of the day";

	$fields = "command,description";

	if ($form->formHandler("description", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
