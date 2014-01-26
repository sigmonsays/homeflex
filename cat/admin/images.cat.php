<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['images'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/images";
	$form->uniqueField = "id";

	$form->setFieldType("name", "text", 40);
	$form->setFieldType("image", "file", "$localPath/images", "/homeflex/images", 'image');

	$form->title = "Images";

	$fields = "name,image";

	if ($form->formHandler("name", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
