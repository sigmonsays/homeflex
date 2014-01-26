<?
	if (!defined("VALID")) die;

	$dbi = new dbi(DB_TYPE);

	$dbi->db->connect(DB_HOST, DB_USER, DB_PASS);

	if (!$dbi->db->select_db(DB_DATABASE)) {
		?>
		<H2>Critical</H2>
		Critical mySQL error

		<H2>Mysql said</H2>
		<?
			echo $dbi->db->error();
			die;
	}
?>
