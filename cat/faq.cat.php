<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['faq'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/faq";
	$form->uniqueField = "id";

	$form->setFieldType("question", "textarea", 10, 40);
	$form->setFieldType("answer", "textarea", 10, 40);
	$form->setFieldType("category", "select", $form->selectSource("category", $dbTables['faq_cat'], "id", "name"));

	$form->mode = "list,search,view";

	$form->linkField("question", "$urlPrefix/faq/?$form->uniqueField=\$id&$form->actionVariable=view");

	$form->sqlOrderBy = "ORDER BY `category`";

	$form->title = "Frequently Asked Questions";


	if ($form->formHandler("question,category", -1, -1, "question,answer")) {
		require("cat/$cat.cat.php");
	}
?>
