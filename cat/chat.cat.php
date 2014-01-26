<?
	if (!defined("VALID")) die;

	$includeLevel++;
	$maxLines = 50;
	$maxMessageLength = 100;

/*
	$size = "16x16_";
	$smilies = array(
		'O:-)'	=>	$size . "angel.gif",
		':-D'	=>	$size . "bigsmile.gif",
		':-!'	=>	$size . "burp.gif",
		':-X'	=>	$size . "crossedlips.gif",
		':\'('	=>	$size . "cry.gif",
		':-['	=>	$size . "embarrassed.gif",
		':-*'	=>	$size . "kiss.gif ",
		':-$'	=>	$size . "moneymouth.gif",
		':-('	=>	$size . "sad.gif",
		':('	=>	$size . "sad.gif",
		'=-O'	=>	$size . "scream.gif",
		':-)'	=>	$size . "smile.gif",
		':)'	=>	$size . "smile.gif",
		'8-)'	=>	$size . "smile8.gif",
		':-P'	=>	$size . "tongue.gif",
		':-/'	=>	$size . "think.gif",
		';-)'	=>	$size . "wink.gif",
		'>:o'	=>	$size . "yell.gif");
*/

	$smileKeys = array(	"/" . preg_quote('O:-)', "/") . "/",	"/" . preg_quote(':-D', "/") . "/",
				"/" . preg_quote(':-!', "/") . "/",	"/" . preg_quote(':-X', "/") . "/",
				"/" . preg_quote(':\'(', "/") . "/",	"/" . preg_quote(':-[', "/") . "/",
				"/" . preg_quote(':-*', "/") . "/",	"/" . preg_quote(':-$', "/") . "/",
				"/" . preg_quote(':-(', "/") . "/",	"/" . preg_quote(':(', "/") . "/",
				"/" . preg_quote('=-O', "/") . "/",	"/" . preg_quote(':-)', "/") . "/",
				"/" . preg_quote(':)', "/") . "/",	"/" . preg_quote('8-)', "/") . "/",
				"/" . preg_quote(':-P', "/") . "/",	"/" . preg_quote(':-/', "/") . "/",
				"/" . preg_quote(';-)', "/") . "/",	"/" . preg_quote('>:o', "/") . "/");

	$prefix = "<IMG SRC=\"" . IMAGES_URL_PREFIX . "/smilies/16x16_";
	$suffix = "\"></IMG>";
	$smilePics = array(
			$prefix . "angel.gif" . $suffix,	$prefix . "bigsmile.gif" . $suffix,
			$prefix . "burp.gif" . $suffix, 	$prefix . "crossedlips.gif" . $suffix,
			$prefix . "cry.gif" . $suffix,		$prefix . "embarrassed.gif" . $suffix,
			$prefix . "kiss.gif" . $suffix,		$prefix . "moneymouth.gif" . $suffix, 
			$prefix . "sad.gif" . $suffix,		$prefix . "sad.gif" . $suffix,
			$prefix . "scream.gif" . $suffix,	$prefix . "smile.gif" . $suffix,
			$prefix . "smile.gif" . $suffix, 	$prefix . "smile8.gif" . $suffix,
			$prefix . "tongue.gif" . $suffix, 	$prefix . "think.gif" . $suffix,
			$prefix . "wink.gif" . $suffix, 	$prefix . "yell.gif" . $suffix);


	switch ($func) {
		case "login":

			if (headers_sent()) {
				unset($func, $arg1);
				require("cat/$cat.cat.php");
			} else {
				$name = $_POST['name'];
				$website = $_POST['website'];

				$name = trim(strip_tags($name));
				$website = trim(ereg_replace("^http://", "", strip_tags($website)));
				$id = base64_encode("$name:$website");
				setcookie("chatUserID", $id, mktime(0, 0, 0, 0, 0, date("Y") + 2), COOKIE_PATH);
				header("Location: $urlPrefix/chat/id/$id");
			}
			break;

		case "logout":
			if (headers_sent()) {
				?>
				<H2>Are you Sure?</H2>
				<A HREF="<?= "$urlPrefix/chat/logout/?nocontent=1" ?>">Logout Now</A>
				<?
			} else {
				setcookie("chatUserID", "", time() - 3600, "/");
				header("Location: $urlPrefix/chat");
			}
			break;

		case "id":
			list($user, $website) = split(":", base64_decode($arg1));
			$user = trim($user);
			$website = trim($user);

			if (empty($user)) {
				unset($func, $arg1);
				require("cat/$cat.cat.php");
			} else {
				?>
				<H2>Chatting at <?= $siteTitle ?></H2>
				<IFRAME NAME="conversation" WIDTH="90%" SRC="<?= "$urlPrefix/chat/conversation/$arg1/?nocontent=1" ?>"></IFRAME>
				<INPUT TYPE="hidden" NAME="nocontent" VALUE=1>
				<?
					if (ereg("Netscape", $_SERVER['HTTP_USER_AGENT']))
						$onSubmit = "onSubmit=\"sendMessage();\"";
					else
						$onSubmit = "";
					echo "<FORM NAME=\"chatForm\" $onSubmit TARGET=\"conversation\" METHOD=\"post\" ACTION=\"$urlPrefix/chat/conversation/$arg1/?nocontent=1\">";
				?>
				<SCRIPT LANGUAGE="JavaScript1.2">
					function sendMessage() {
							document.chatForm.submit();
							document.chatForm.message.value = '';
					}
					function insertSmile(x) {
						document.chatForm.message.value = document.chatForm.message.value + x;
					}
				</SCRIPT>
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Smilies<BR><SELECT NAME="smiles" OnChange="insertSmile(this.value);">
                                        <OPTION VALUE=""></OPTION>
                                        <OPTION VALUE="O:-)">O:-)</OPTION>
					<OPTION VALUE=":-D">:-D</OPTION>
					<OPTION VALUE=":-!">:-!</OPTION>
					<OPTION VALUE=":-X">:-X</OPTION>
					<OPTION VALUE=":'(">:'(</OPTION>
					<OPTION VALUE=":-[">:-[</OPTION>
					<OPTION VALUE=":-*">:-*</OPTION>
					<OPTION VALUE=":-$">:-$</OPTION>
					<OPTION VALUE=":-(">:-(</OPTION>
					<OPTION VALUE=":(">:(</OPTION>
					<OPTION VALUE="=-O">=-O</OPTION>
					<OPTION VALUE=":-)">:-)</OPTION>
					<OPTION VALUE=":)">:)</OPTION>
					<OPTION VALUE="8-)">8-)</OPTION>
					<OPTION VALUE=":-P">:-P</OPTION>
					<OPTION VALUE=":-/">:-/</OPTION>
					<OPTION VALUE=";-)">;-)</OPTION>
					<OPTION VALUE=">:o">>:o</OPTION>
                                        </SELECT></TD>

					<TD>
					Type your message in the box below <B><?= $user ?></B><BR>
					<INPUT TYPE="text" NAME="message" SIZE=50 MAXLENGTH="<?= $maxMessageLength ?>"> &nbsp; 
					<INPUT NAME="sendButton" TYPE="submit" VALUE="Send"> &nbsp; 
					<INPUT TYPE="reset" VALUE="Clear"> &nbsp;
					<INPUT TYPE="buttoN" VALUE="Logout" OnClick="document.location='<?= "$urlPrefix/chat/logout" ?>';">
					</TD>
				</TR>
				</TABLE>
				</FORM>
				<?
			}
			break;

		case "conversation":
			$message = stripslashes($_POST['message']);

			$conversation = file("chat.txt");
			$c = count($conversation);
			if ($c > $maxLines) {
				$time = time();
				copy("chat.txt", CHAT_DIRECTORY . "/$time-chat.txt");
				$f = fopen("chat.txt", "w+");
				fputs($f, "$time<S><B><I>System</I></B><S><S>Chat log hit $maxLines lines, chat file reset\n");
				fclose($f);
			}

			echo "<META HTTP-EQUIV=Refresh CONTENT=\"10; URL=$urlPrefix/chat/conversation/$arg1/?nocontent=1\">";
			list($user, $website) = split(":", base64_decode($arg1));
			if (!empty($message)) {
				$message = substr($message, 0, $maxMessageLength);
				$message = strip_tags($message, "<a><b><i><u>");
				$f = fopen("chat.txt", "a+");
				if ($f) {
					$time = time();

					$message = preg_replace($smileKeys, $smilePics, $message, 3);

					fputs($f, "$time<S>$user<S>$website<S>$message\n");
					fclose($f);
				} else
					echo "Error";
			}


			$conversation = array_reverse(file("chat.txt"));
			$c = count($conversation);
			for($i=0; $i<$c; $i++) {
				list($time, $user, $website, $text) = split("<S>", $conversation[$i]);
				if (!empty($website)) {
					if (!ereg("^http://", $website)) $website = "http://$website";
					$userName = "<A TARGET=\"_blank\" HREF=\"$website\">$user</A>";
				} else
					$userName = "<B>$user</B>";


				echo date("Y.m.d", $time) . " : $userName - $text<BR>";
			}
			break;

		default:
			if (!empty($_COOKIE['chatUserID']))
				list($userName, $website) = split(":", base64_decode($_COOKIE['chatUserID']));
			$userName = strip_tags($userName);
			$website = "http://" . strip_tags($website);

			?>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/chat/login" ?>">
			<INPUT TYPE="hidden" NAME="nocontent" VALUE=1>
			<TABLE WIDTH="60%" BORDER=0 ALIGN="center">
			<TR><TD COLSPAN=2>
				<H2>Chat</H2>
				Enter a Name and optionally your website then click Login
			</TD></TR>

			<TR>
				<TD>Name</TD>
				<TD><INPUT TYPE="text" NAME="name" VALUE="<?= $userName ?>"></TD>
			</TR>

			<TR>
				<TD>Website</TD>
				<TD><INPUT TYPE="text" NAME="website" SIZE=40 VALUE="<?= $website ?>"></TD>
			</TR>

			<TR><TD COLSPAN=2 ALIGN="right"><INPUT TYPE="submit" VALUE="Login"></TD></TR>
			
			</TABLE>
			</FORM>
			<?
			break;
	}
?>
