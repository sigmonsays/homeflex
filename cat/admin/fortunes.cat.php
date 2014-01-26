<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, "fortunes", DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/fortunes";
	$form->uniqueField = "id";

	$form->setFieldType("fortune", "textarea", 10, 30);

	$form->mode = "add,edit,delete,list,search,view";

	$form->title = "Fortune";

	$fields = "fortune";

	if ($form->formHandler("fortune", $fields, $fields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
