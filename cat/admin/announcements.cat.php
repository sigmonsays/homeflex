<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['announcements'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/announcements";
	$form->uniqueField = "id";

	$form->setFieldType("subject", "text", 40);
	$form->setFieldType("message", "textarea", 10, 40);

	$form->title = "Announcements";

	if ($form->formHandler("subject", "subject,message", "subject,message")) {
			unset($_GET['action']);
			require("cat/$cat.cat.php");
	}
?>
