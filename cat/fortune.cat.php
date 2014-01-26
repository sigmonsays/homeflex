<H2>Todays Fortune</H2>
<DIV CLASS="row2" STYLE="border: 1px solid black; padding: 1em; margin: 1em;">
<BR>
<?
	if (!function_exists("GregorianToJD")) {
		function GregorianToJD ($month,$day,$year) {
			if ($month > 2) {
				$month = $month - 3;
			} else {
				$month = $month + 9;
				$year = $year - 1;
		}
		$c = floor($year / 100);
		$ya = $year - (100 * $c);
		$j = floor((146097 * $c) / 4);
		$j += floor((1461 * $ya)/4);
		$j += floor(((153 * $month) + 2) / 5);
		$j += $day + 1721119;
		return $j;
		}
	}

	$sql = $dbi->db->query("SELECT COUNT(*) FROM {$dbTables['fortunes']}");
	if ($sql && $dbi->db->num_rows($sql)) {
		list($count) = $dbi->db->fetch_row($sql);

		$day = GregorianToJD(date("n"), date("j"), date("Y"));

		$id = $day % $count;

		$sql = $dbi->db->query("SELECT fortune FROM {$dbTables['fortunes']} WHERE id='$id'");
		if ($sql && $dbi->db->num_rows($sql)) {
			echo "<B>Fortune #$id</B><BR><BR>";

			list($fortune) = $dbi->db->fetch_row($sql);
			echo nl2br($fortune);
		} else {
			echo "No fortune provided for this day!!";
		}
		
	}
?>
<BR>
</DIV>
