<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['changelog'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/changelog";
	$form->uniqueField = "id";

	$form->setFieldType("subject", "text", 40);
	$form->setFieldType("text", "textarea", 10, 40);
	$form->sqlOrderBy = "ORDER BY `when` DESC";
	$form->mode = "list,search,view";
	$form->setFieldFunction("when", "timestamp", "built-in");

	$form->linkField("subject", "$urlPrefix/changelog/?$form->uniqueField=\$id&$form->actionVariable=view");

	if ($form->formHandler("id,when,subject", -1, -1, "id,when,subject")) {
		require("cat/$cat.cat.php");
	}
?>
