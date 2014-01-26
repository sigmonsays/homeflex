<?
	if (!defined("VALID")) die;


			$form = new sqlForm($dbi, $dbTables['faq_cat'], DB_DATABASE);
			$form->urlPrefix = "$urlPrefix/admin/faqCategories";
			$form->uniqueField = "id";

			$form->title = "FAQ Categories";

			$form->additionalLink("FAQ", "$urlPrefix/admin/faq");

			$fields = "name";

			if ($form->formHandler($fields, $fields, $fields, $fields)) {
				unset($_GET['action']);
				require("cat/$cat.cat.php");
			}
?>
