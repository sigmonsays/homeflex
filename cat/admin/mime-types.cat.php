<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['mimetypes'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/mime-types";
	$form->uniqueField = "extension";
	$form->sqlOrderBy = "ORDER BY extension ASC";

	$form->setFieldType("type", "text", 50);
	$form->setFieldType("extension", "text", 5);

	$form->title = "MIME Types";

	$form->mode = "add,edit,delete,list,search";

	if ($form->formHandler("type,extension", "type,extension")) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
