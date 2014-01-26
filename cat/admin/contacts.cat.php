<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, "contacts", DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/contacts";
	$form->uniqueField = "id";
	$form->sqlOrderBy = "ORDER BY `full_name` ASC";

	$form->setFieldType("notes", "textarea", 10, 50);
	$form->setFieldType("chick", "boolean");

	$form->mode = "list,add,edit,delete,search,view";

	$addFields = "full_name,home_phone,mobile_phone,address,notes,email,aim,chick";


	if ($form->formHandler("full_name,home_phone,mobile_phone", $addFields, $addFields, $addFields)) {
		require("cat/$cat.cat.php");
	}
?>
