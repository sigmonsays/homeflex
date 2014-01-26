<?
	if (!defined("VALID")) die;

			$form = new sqlForm($dbi, $dbTables['faq'], DB_DATABASE);
			$form->urlPrefix = "$urlPrefix/admin/faq";
			$form->uniqueField = "id";

			$form->title = "FAQ";

			$form->setFieldType("question", "textarea", 10, 50);
			$form->setFieldType("answer", "textarea", 10, 50);
			$form->setFieldType("category", "select", $form->selectSource("category", $dbTables['faqCategories'], "id", "name") );

			$form->additionalLink("Categories", "$urlPrefix/admin/faqCategories");

			$fields = "question,answer,category";

			if ($form->formHandler("question", $fields, $fields, $fields)) {
				unset($_GET['action']);
				require("cat/$cat.cat.php");
			}
?>
