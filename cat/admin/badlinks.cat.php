<?
	if (!defined("VALID")) die;


	$form = new sqlForm($dbi, $dbTables['badlinks'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/badlinks";
	$form->uniqueField = "id";
	$form->sqlOrderBy = "ORDER BY id DESC";

	$form->setFieldType("command", "textarea", 10, 40);
	$form->setFieldType("user", "text", 10);
	$form->setFieldType("id", "text", 5);
	$form->setFieldType("referer", "text", 50);
	$form->setFieldType("request_uri", "text", 50);
	$form->setFieldType("when", "date", "F d Y", "sql-timestamp");

	$form->title = "Bad Links";

	$addFields = "when,ip,user,referer,request_uri";

	if ($form->formHandler("ip,user,request_uri", $addFields, $addFields)) {
		unset($_GET['action']);
		require("cat/$cat.cat.php");
	}
?>
