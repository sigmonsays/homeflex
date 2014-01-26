<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, "away_messages", DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/away-messages";

	$form->uniqueField = "id";

	$form->setFieldType("message", "textarea", 15, 60);

	$fields = "person,message";

	if ($form->formHandler($fields, $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
