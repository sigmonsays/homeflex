<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "sendMail":
			$from = $_POST['from'];
			$subject = $_POST['subject'];
			$message = $_POST['message'];

			if (empty($message)) {
				echo "You need to type atleast a message!<BR>";
				unset($func);
				require("cat/$cat.cat.php");
			} else {
				$message = nl2br($message);
				if (mail_html($contact['email'], $from, "$subject", $message))
					echo "Your mail was Sent!<BR>";
				else
					echo "I couldn't send your mail!!!!!<BR>";
				unset($func);
				require("cat/news.cat.php");
			}
			break;

		default:
		?>
		<H2>Contact</H2>
		<TABLE WIDTH="50%" BORDER=0 ALIGN="center">
		<TR>
			<TD>Mail:</TD>
			<TD><A HREF="mailto:<?= $contact['email'] ?>"><?= $contact['email'] ?></A></TD>
		</TR>

		<TR>
			<TD>AIM:</TD>
			<TD><A HREF="aim:goim?screenname=<?= $contact['aim'] ?>"><?= $contact['aim'] ?></A></TD>
		</TR>

		<TR><TD COLSPAN=2>&nbsp;</TD></TR>

		<FORM METHOD="post" ACTION="<?= "$urlPrefix/contact/sendMail" ?>">
		<TR>
			<TD>To:</TD>
			<TD><A HREF="mailto:<?= $contact['email'] ?>"><?= $contact['email'] ?></A></TD>
		</TR>

		<TR>
			<TD>From:</TD>
			<TD><INPUT TYPE="text" NAME="from"> &nbsp; (If you want a reply)</TD>
		</TR>

		<TR>
			<TD>Subject:</TD>
			<TD><INPUT TYPE="text" NAME="subject" SIZE=50></TD>
		</TR>

		<TR>
			<TD VALIGN="top">Message</TD>
			<TD>Feel free to use HTML, new lines are automatically converted to breaks: <B>&lt;BR&gt;</B>.<BR>
				<TEXTAREA NAME="message" ROWS=10 COLS=50></TEXTAREA>
			</TD>
		</TR>

		<TR><TD COLSPAN=2 ALIGN="center">
			<INPUT TYPE="submit" VALUE="Send"> &nbsp; <INPUT TYPE="reset" VALUE="Start Over">
		</TD></TR>
		</FORM>
		</TABLE>
		<?
	}
?>
