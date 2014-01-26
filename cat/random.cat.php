<?
	switch ($func) {
		default:
			$l_path = "$localPath/random/randomness";
			$r_path = "/homeflex/random/randomness";
			?>
			<H1>Randomosity</H1>
			Here is some totally random stuff for you

			<H2>Graph</H2>
			<IMG SRC="<?= "$r_path/random-graph.png" ?>">

			<H2>Word</H2>
			<?
			@list($word) = file("$l_path/random-word.txt");
			echo "<B>$word</B><BR>";

			@$def = join("", file("$l_path/random-definition.txt"));
			echo $def;

			?>
			<H2>Number</H2>
			<?
			@list($number) = file("$l_path/random-number.txt");
			echo formatNumber($number);

			?>
			<H2>MIME Type</H2>
			<?
			@list($stuff) = file("$l_path/random-mime.txt");
			list($type, $ext) = split(":", $stuff);

			echo "$type<BR>";
			echo "<B>Extension:</B> $ext";

			?>
			<H2>Project</H2>
			<?
			@list($stuff) = file("$l_path/random-project.txt");
			list($id, $name) = split(":", $stuff);

			echo "<A HREF=\"$urlPrefix/projects/id/$id\">$name</A>";

			?>
			<H2>Port</H2>
			<?
			@list($id) = file("$l_path/random-port.txt");
			$qry = "SELECT * FROM {$dbTables['services']} WHERE id='$id'";
			$sql = $dbi->db->query($qry);
			if ($sql && $dbi->db->num_rows($sql)) {
				$obj = $dbi->db->fetch_object($sql);
				echo "<B>Port:</B> $obj->service ($obj->port)<BR>";
				echo "<B>Protocol:</B> $obj->protocol<BR>";
				echo "<B>Description:</B><BR>";
				echo $obj->description;
			}
			?>
			<H2>Command</H2>
			<?
			@list($id) = file("$l_path/random-command.txt");
			$qry = "SELECT * FROM {$dbTables['cotd']} WHERE `day`='$id'";
			$sql = $dbi->db->query($qry);
			if ($sql && $dbi->db->num_rows($sql)) {
				$obj = $dbi->db->fetch_object($sql);

				echo "<B>Description</B><BR>";
				echo htmlspecialchars($obj->description);

				echo "<P>";

				echo "<B>Command</B><BR>";
				echo htmlspecialchars($obj->command);
			}

			?>
			<H2>Skin</H2>
			<?
			@list($stuff) = file("$l_path/random-skin.txt");
			list($id, $name) = split(":", $stuff);
			echo "<B>$name</B>.... &nbsp; ";
			echo "<A HREF=\"$urlPrefix/guest/set/skin/$id\">Check it out</A>";

			?>
			<H2>Linux Process</H2>
			<?
			@list($stuff) = file("$l_path/random-process.txt");
			echo "<PRE>";
			echo "<FONT SIZE=+1>";
			echo "<B>USER</B>      <B>PID</B> <B>%CPU</B> <B>%MEM</B>   <B>VSZ</B>  <B>RSS</B> <B>TTY</B>      <B>STAT</B> <B>START</B>   <B>TIME</B> <B>COMMAND</B>";
			echo "<BR>";
			echo htmlspecialchars($stuff);
			echo "</FONT></PRE>";

			@list($color) = file("$l_path/random-color.txt");
			?>
			<H2>Color</H2>
			<B>Hex Value:</B> <?= $color ?><BR><BR>
			<TABLE STYLE="border: 1px solid black">
			<TR><TD BGCOLOR="<?= $color ?>">&nbsp; &nbsp; &nbsp; </TD></TR>
			</TABLE>
			<?

			break;
	}
?>
