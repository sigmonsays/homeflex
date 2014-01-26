<?
if (!defined("VALID")) die;

require("$localPath/inc/toolbar.php");


function getmicrotime() {
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
}

function getInclude($inc) {
	extract($GLOBALS);

	ob_start();
	require($inc);
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function getOptions($userid) {
	global $dbi, $dbTables;
	$dbi->db->query("SELECT variable,value FROM {$dbTables['preferences']} WHERE userid='$userid'");
	while (list($var, $val) = $dbi->db->fetch_row()) {
		$rv[$var] = $val;
	}
	return $rv;
}

function setOption($variable, $value) {
	global $dbi, $dbTables;
	$qVariable = str_replace("'", "", stripslashes($variable));
	$qValue = mysql_escape_string($value);

	$qry = "SELECT `id`,`value` FROM {$dbTables['preferences']} WHERE userid='{$_SESSION['s_id']}' AND variable='$qVariable'";
	$dbi->db->query($qry);

	if ($dbi->db->num_rows()) {
		list($id, $oldValue) = $dbi->db->fetch_row();
		$dbi->db->query("UPDATE {$dbTables['preferences']} SET value='$qValue' WHERE id='$id'");
		return $oldValue;

	} else {
			$qry = "INSERT INTO {$dbTables['preferences']} VALUES(NULL, '{$_SESSION['s_id']}', '$qVariable', '$qValue')";
			$dbi->db->query($qry);
	}
}


/* 	used in source.cat.php
 *	I use array_walk() and the functions used in that must be user defined.
 *	Yes. It is annoying =)
 */
function reverseString(&$str) {
	$str = strrev($str);
}

function _url($url, $name, $extra = "") {
	return "<A HREF=\"$url\" $extra>$name</A>";
}
function echoURL($url, $name, $extra = "") {
	echo _url($url, $name, $extra);
}

function mail_html($to, $from, $subject, $body) {
 
        $headers = "From: $from\r\n"
        . "Content-type: text/html; charset=iso-8859-1\r\n";
        return mail($to, $subject, $body, $headers);
}

function fortune() {
	exec("/usr/local/bin/fortune", $buf);
	$buf = join("<br>", $buf);
	$buf = ereg_replace("[\"']", "", $buf);
	return $buf;
}

function current_date() {
	return date("Y.m.d", time());
}

function write_menu($menu, $max) {

	if ($max == -1) $j = count($menu); else $j = $max * 2;

	echo "<tr><td>";
        for($i=0; $i<$j; $i+=2) {

                $item = $menu[$i];
                $link = $menu[$i + 1];

                if (substr($item, 0, 2)=="||") {
                        $x = substr($item, 2);
                        echo "<a href=\"$link\"><font size=+1><b>$x</b></font></a><br>\n";
                } elseif (substr($item, 0, 1)=="|") {
                        $x = substr($item, 1);
                        echo "<li><a href=\"$link\">$x</a></li>\n";
                } elseif (substr($item, 0, 1)=="-") {
                        echo "<br>\n";
                } else {
                        echo "<a href=\"$link\">$item</a>\n";
                }
        }
	echo "</td></tr>";

}

        function banner($title, $msg) {
                ?>
                <table width=60% border=1 align=center cellspacing=0 cellpadding=0><tr><td class=none valign=top>
                <table width=100% border=0 cellspacing=0 cellpadding=2>
                 <tr><th><?= $title ?></th></tr>
                 <tr><td class=none align=center valign=top><?= $msg ?></td></tr>
                </table>
		</td></tr></table>
                <?
        }

	function javascript($js) {
		$rv.="<script language=\"javascript\">";
		$rv.="$js\n";
		$rv.="</script>\n";
		return $rv;
	}

	function jsRedir($url)
	{
		echo "<script language=\"javascript\">document.location=\"$url\";</script>";
		die;
	}


	function formatTime($timestamp, $format = "M d, Y h:ia")
        {
                //example time 20010627110324

                $year=substr($timestamp, 0, 4);
                $month=substr($timestamp, 4, 2);
                $day=substr($timestamp, 6,2);
                $hour=substr($timestamp, 8, 2);
                $minute=substr($timestamp, 10,2);
                $second=substr($timestamp, 12,2);

                return date($format, 
		mktime($hour, $minute, $second, $month, $day, $year));
        }


	//make a nice looking file size out of given bytes. IE: 1024 yields 1M
	function formatSize($b)
	{
		//1024		k
		//1048576	m
		//1073741824	g

		if ($b >= 1073741824)
			$thesize = round($b / 1073741824 * 100) / 100 . "G"; 
		elseif ($b >= 1048576)
			$thesize = round($b / 1048576 * 100) / 100 . "MB";
		elseif ($b >= 1024)
			$thesize = round($b / 1024 * 100) / 100 . "K"; 
		else
			$thesize = $b . "b";

		return $thesize;
	}

	//make a 'nice' looking number out of the one sent in. IE: 125415244 yields 12,541,244
	$c=3;
	function formatNumber($number)
	{
		for($i=strlen($number); $i>-1; $i--)
		{
			$rv .= substr($number, $i, 1);
			$c++;
			if (($c%3)==1 && $i!=0) $rv .= ",";
		}
		return substr(strrev($rv), 0, $i);
		
	}


	function loadDirectory($dir, $match = "/.*/", $moreOpts = "") {
		@$d = opendir($dir);
		$options = split(",", $moreOpts);
		if ($d) {
			readdir($d);
			readdir($d);
			$files = array();
			while ($file = readdir($d)) {
				if (@filetype("$dir/$file") == 'file' && preg_match($match, $file)) {
					if (in_array("ASSOCIATIVE_ARRAY", $options))
						$files[$file] = $file;
					else
						$files[] = $file;
				}
			}
			closedir($d);
			return $files;
		} else
			return 0;
	}

	function getUserNameFromID($userid) {
		global $dbi, $dbTables;
		$sql = $dbi->db->query("SELECT user FROM {$dbTables['users']} WHERE id='$userid'");
		if ($dbi->num_rows()) {
			list($user) = $dbi->db->fetch_row();
			return $user;
		} else
			return FALSE;
	}

	function textSize($text) {
		$text = strtolower($text);
		$num = ereg_replace("[^0-9]", "", $text);
		$suffix = ereg_replace("[0-9]", "", $text);

		if ($suffix == 'b')
			return $num;
		elseif ($suffix == 'k')
			return ($num * 1024);
		elseif ($suffix == 'm')
			return ($num * 1048576);
		elseif ($suffix == 'g')
			return ($num * 1048576000);
		else
			return $num;
}

function div_box($heading, $msg) {
	echo "<DIV CLASS=\"row1\" STYLE=\"border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; margin-left: 1em;"
		. " margin-right: 1em; padding: 0.3em;\">\n";
	echo "<B>$heading</B>\n";
	echo "</DIV>\n";
	echo "<DIV CLASS=\"row2\" STYLE=\"border: 1px solid black; padding: 1em; margin-left: 1em; margin-right: 1em; margin-bottom: 1em;\">\n";
	echo $msg;
	echo "</DIV>\n";
}

?>
