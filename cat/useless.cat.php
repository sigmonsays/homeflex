<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "talkfilters":
			$cmds = array(	"b1ff", "brooklyn", "chef", "cockney", "drawl", "fudd", "funetak", "jethro", "jive", "kraut", 
					"pansy", "postmodern", "redneck", "valspeak", "warez");

			$cmd = $_POST['cmd'];

			$theText = $_POST['theText'];

			?>
			<H2>Talk Filter</H2>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/useless/talkfilters" ?>">
			<INPUT TYPE="hidden" NAME="post" VALUE=1>
			<SELECT NAME="cmd">
			<?
				$c = count($cmds);
				for($i=0; $i<$c; $i++) {
					$sel = ($cmds[$i] == $cmd) ? "SELECTED" : "";
					echo "<OPTION $sel VALUE=\"$cmds[$i]\">$cmds[$i]</OPTION>\n";
				}
			?>
			</SELECT>

			<BR><BR>Text<BR><BR>
			<TEXTAREA NAME="theText" ROWS=10 COLS=40><?= htmlspecialchars($theText) ?></TEXTAREA>
			<?
				if ($_POST['post']) {
					$rcmd = "/usr/bin/" . escapeshellcmd($cmd);
					$args = $theText;
					$fds = array();

					$descriptorspec = array(
						0 => array("pipe", "r"),
						1 => array("pipe", "w")
					);

					$fp = proc_open($rcmd, $descriptorspec, $fds);

					fwrite($fds[0], $args);
					fclose($fds[0]);

					echo "<BR><BR>Output:<BR><BR>";
					echo "<DIV STYLE=\"border: 1px solid black; padding: 1em; background-color: #ffffff;\">";

					while (!feof($fds[1])) {
						echo nl2br(fgets($fds[1], 1024));
					}
					fclose($fds[1]);

					proc_close($fp);

					echo "</DIV><BR>";
				}

			?>
			<BR><BR>
			<INPUT TYPE="submit" VALUE="Convert">
			</FORM>
			<?

			break;

		case "base10ip":
			$ip = $_POST['ip'];
			?>
			<H2>Enter an IP</H2>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/useless/base10ip" ?>">
			<INPUT TYPE="text" NAME="ip[]" SIZE=4> &nbsp; 
			<INPUT TYPE="text" NAME="ip[]" SIZE=4> &nbsp; 
			<INPUT TYPE="text" NAME="ip[]" SIZE=4> &nbsp; 
			<INPUT TYPE="text" NAME="ip[]" SIZE=4> &nbsp; 
			<INPUT TYPE="submit" VALUE="Submit">
			</FORM>
			<?

			if (!empty($ip)) {
				echo "<h2>Results</h2>";
				$mask=array(16777216, 65536, 256, 1);
				for($i=0; $i<count($ip); $i++)
				$x+=$mask[$i] * $ip[$i];
					echo "You gave me: " . join(".", $ip) . "<br>";
				echo "I give you: $x<br>";
				echo "Now just treat that number as an IP addy.";
			}
			break;

		case "yourInfo":

			?>
			<H2>Your Information</H2>
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<TR>
				<TD>IP Address / Port</TD>
				<TD><?= $_SERVER['REMOTE_ADDR'] . " : " . $_SERVER['REMOTE_PORT'] ?></TD>
			</TR>

			<TR>
				<TD>Host Name</TD>
				<TD><?= gethostbyaddr($_SERVER['REMOTE_ADDR']) ?></TD>
			</TR>
			</TABLE>
			<?
			break;

		case "nmblookup":
			$ip = $_POST['ip'];

			?>
			<H2>NMB Lookup</H2>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/useless/nmblookup" ?>">
			<TABLE WIDTH="50%" BORDER=0 ALIGN="center">
			IP Address: <INPUT TYPE="text" NAME="ip" VALUE="<?= strip_tags($ip) ?>"> &nbsp;
			<INPUT TYPE="submit" VALUE="OK">
			</TABLE>
			</FORM>
			<?

			if (empty($ip)) {
				echo "<P>Enter an IP Address to get the Netbios Name";
			} else {
				$arg1 = escapeshellarg(strip_tags($ip));
				exec("/usr/local/samba/bin/nmblookup -A $arg1", $output);

				echo "<P><PRE>\n";
				echo htmlspecialchars(join("\n", $output));
				echo "</PRE>\n";
			}
			break;

		case "bgContrib":
		        @$contents = join("", file("/htdocs/bgWealthClock.html"));
        		if (!empty($contents)) {


	        	        // <tr><td>Microsoft Stock Price:<td align=right> $46.23
        	        	$a = preg_quote('<tr><td>Microsoft Stock Price:<td align=right> $', "'");
	                	preg_match("'$a" . '([0-9.]+)' . "'", $contents, $matches);
		                list(, $stockPrice) = $matches;

	        	        // <tr><td>Bill Gates's Wealth:<td align=right> $52.206611 billion
        	        	$a = preg_quote('<tr><td>Bill Gates\'s Wealth:<td align=right> $', "'");
	        	        preg_match("'$a" . '([0-9.]+) billion' . "'", $contents, $matches);
        	        	list(, $bgWealth) = $matches;
	
        	        	// <tr><td>U.S. Population:<td align=right> 288,187,962
	        	        $a = preg_quote('<tr><td>U.S. Population:<td align=right> ', "'");
        	        	preg_match("'$a" . '([0-9,]+)' . "'", $contents, $matches);
	                	list(, $population) = $matches;
	
        		        // <b>Your Personal Contribution:</b></font><td align=right>  <font size=+1><b>$181.15</font></b>
                		$a = preg_quote('<b>Your Personal Contribution:</b></font><td align=right>  <font size=+1><b>$', "'");
	                	$b = preg_quote('</font></b>', "'");
	        	        preg_match("'$a" . '([0-9.]+)' . "$b'", $contents, $matches);
        		        list(, $contribution) = $matches;
	
	        	        ?>
				<H2>Uhh.. Yeah Bill Gates Sucks (<SMALL>The COck</SMALL>)</H2>
        	        	<TABLE WIDTH="50%" BORDER=0 ALIGN="center" CELLPADDING=5>
		                <TR><TD>
        		        Your Personal Contribution to bill Gates: <B>$<?= $contribution ?></B><BR><BR>

                		<SMALL>
		                Parsed from <A HREF="http://philip.greenspun.com/WealthClock">http://philip.greenspun.com/WealthClock</A>.<BR>
        		        Microsofts Stock Price is <B>$<?= $stockPrice ?></B>, He's worth <B>$<?= $bgWealth ?> Billion!</B>, and the US Population is
                		<B><?= $population ?></B>.
		                	</SMALL>
        		        </TD></TR>
                		</TABLE>
	                	<?
	        	}
			break;

		case "fortune":
			unset($output);
			exec("/usr/games/gfortune 2>/dev/null", $output);
			$quote = strip_tags(join("<BR>", $output), "<BR>");

			?>
			<H2>Fortune</H2>
			<DIV STYLE="border: 1px solid black; padding: 1em; background-color: #ffffff;">
			<?= $quote ?>
			</DIV>
			<BR><BR>
			<?

			break;

		case "eotd":
			echo "<H2>Excuse of The Day</H2>";
			// <div align="center"><p><b><font face="Arial, Helvetica, sans-serif">The Cause Of The Problem is Digital Manipulator exceeding velocity parameters
			$a = preg_quote('<div align="center"><p><b><font face="Arial, Helvetica, sans-serif">', "'");
			$contents = join("", file("/htdocs/excuseOfTheDay.html"));
			preg_match("'$a" . '(.*)' . "'", $contents, $matches);
			list(, $excuse) = $matches;
			echo "<PRE>$excuse</PRE>";
			break;


		default:
			?>
			<H2>Useless Stuff.. I didn't know where to put</H2>

			<LI><A HREF="<?= "$urlPrefix/old" ?>">Old Website Stuff</A></LI>
			<LI><A HREF="<?= "$urlPrefix/useless/eotd" ?>">Excuse of The Day</A></LI>
			<LI><A HREF="<?= "$urlPrefix/useless/fortune" ?>">Fortune</A></LI>
			<LI><A HREF="<?= "$urlPrefix/useless/bgContrib" ?>">YOUR Contributions to Bill Gates</A></LI>
			<LI><A HREF="<?= "$urlPrefix/useless/nmblookup" ?>">NMB Lookup</A></LI>
			<LI><A HREF="<?= "$urlPrefix/useless/yourInfo" ?>">Your Info</A></LI>
			<LI><A HREF="<?= "$urlPrefix/useless/base10ip" ?>">Convert an IP to base10</A></LI>
			<LI><A HREF="<?= "$urlPrefix/useless/talkfilters" ?>">Talk Filters</A></LI>

			<?
			break;
	}
?>
