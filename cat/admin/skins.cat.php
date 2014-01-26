<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['skins'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/skins";
	$form->setFieldType("active", "checkbox", 1);
	$form->setFieldFunction("active", "boolean", "built-in", "On/Off");
	$form->sqlOrderBy = "ORDER BY active DESC, name ASC";

	$fields = "name,directory,active";

	if ($form->formHandler("name,active", $fields, $fields)) {

		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
