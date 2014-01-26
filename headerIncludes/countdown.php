<?
	$now = time();

	$date = date("YmdHis", $now);

	$qry = "SELECT * FROM {$dbTables['countdown']} WHERE `date` >= '$date' ORDER BY `date` ASC LIMIT 1";

	$dbi->db->query($qry);

	if ($dbi->db->num_rows()) {
		$thing = $dbi->db->fetch_object();

		$then = formatTime($thing->date, "U");

		$days = intval((($then - $now) / 86400) + 1);

		echo "<CENTER>\n";

		if ($days > 1) {
			printf('%d days until %s', $days, $thing->message);
		} else {
			$hours = intval(($then - $now) / 3600);
			printf('%d hours until %s', $hours, $thing->message);
		}

		if (!empty($thing->link)) {
			echo "<BR><A HREF=\"" . $thing->link . "\">" . $thing->link . "</A>\n";
		}
		echo "</CENTER><BR>\n";


	} else {
		echo "Nothing special happening";
	}
?>
