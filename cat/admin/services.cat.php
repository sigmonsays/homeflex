<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['services'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/services";
	$form->uniqueField = "id";

	$form->setFieldType("id", "text", 5);
	$form->setFieldType("description", "textarea", 10, 50);
	$form->setFieldType("protocol", "text", 4);

	$addFields = "service,port,protocol,description";

	if ($form->formHandler("port,service,protocol", $addFields, $addFields, $addFields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
