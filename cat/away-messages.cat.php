<?
	if (!defined("VALID")) die;
	$form = new sqlForm($dbi, "away_messages", DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/away-messages";
	$form->uniqueField = "id";

	$form->setFieldType("person", "text", 20);
	$form->setFieldType("message", "textarea", 10, 40);

	$form->linkField("message", "$urlPrefix/away-messages?$form->uniqueField=\$id&$form->actionVariable=view");

	$form->mode = "add,list,search,view";
	$form->title = "Away Messages";
	$fields = "person,message";

	if ($form->formHandler($fields, $fields, $fields, $fields)) {
		require("cat/$cat.cat.php");
	}
?>
