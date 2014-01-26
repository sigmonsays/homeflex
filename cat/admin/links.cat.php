<?
	if (!defined("VALID")) die;

	$form = new sqlForm($dbi, $dbTables['links'], DB_DATABASE);
	$form->urlPrefix = "$urlPrefix/admin/links";

	$form->sqlOrderBy = "ORDER BY active DESC, priority DESC, title ASC";
	$form->setFieldType("active", "checkbox", 1);

	$form->setFieldFunction("active", "boolean", "built-in");

	$form->setFieldType("url", "text", 50);
	$form->setFieldType("title", "text", 50);
	$form->setFieldType("priority", "select", range(0, 100));
	$form->setFieldType("when", "date", "F d Y", "sql-timestamp");

	$form->title = "Links";
	$form->mode = "add,edit,delete,list,search,view";

	$form->sqlOrderBy = "ORDER BY `when` DESC";

	$form->setFieldType("category", "select", $form->selectSource("category", "linkCategories", "id", "name"));

	if ($form->formHandler("category,when,title,active", "active,category,url,title,priority", "active,category,url,title,priority")) {
			unset($_GET['action']);
			require("cat/$cat.cat.php");
	}
?>
