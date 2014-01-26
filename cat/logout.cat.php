<?
	if (!defined("VALID")) die;
	switch ($func) {
		default:
			if (headers_sent()) {
				echo "<A HREF=\"$urlPrefix/logout/?nocontent=1\">Logout</A>";
			} else {
				if (!empty($_COOKIE['loginID'])) {
					session_start();
					$loginID = session_id();
					session_destroy();
				}
				header("Location: $urlPrefix/login");
			}
			break;
	}
?>
