#!/usr/bin/env php
<?
	require("/etc/homeflex.php");
	require("$localPath/inc/classes.php");
	require("$localPath/inc/db.php");

	$sql = $dbi->db->query("SHOW TABLES");

	echo "# create table generated on " . date("r") . "\n\n";

	while (list($table) = $dbi->db->fetch_row($sql)) {

		$sql2 = $dbi->db->query("SHOW CREATE TABLE `$table`");

		echo "\n\n# table $table\n";
		list(, $create_table) = $dbi->db->fetch_row($sql2);

		echo $create_table;
		echo ";\n";
	}

	$dbi->db->close();
?>


# end of dump
