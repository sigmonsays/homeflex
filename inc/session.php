<?
	if (!defined("VALID")) die;

	$loggedIn = 0;
	$level = 1;

        if (!empty($_COOKIE['loginID'])) {
                //they have a cookie set
		session_start();

                if (empty($_SESSION['s_id'])) {
                        //they have a cookie set, but it's not a valid session
                        @session_destroy();
			setcookie("loginID", "", time() - 3600, COOKIE_PATH);
                        require("main.php");
                        die;
                }
		$loggedIn = 1;

		$level = $_SESSION['s_level'];
        }
?>
