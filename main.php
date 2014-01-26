<?
	require_once("inc/classes.php");
	require_once("inc/db.php");

	require_once("inc/session.php");
	require_once("inc/userPreferences.php");
	require_once("inc/formatLocation.php");

	require("$localPath/inc/template.php");

	if (!isset($siteTheme)) $siteTheme = DEFAULT_THEME;

	if (!file_exists("$localPath/themes/$siteTheme/index.html")) {
			$siteTheme = DEFAULT_THEME;
	}

	if (!file_exists("$localPath/themes/$siteTheme/index.html")) {
		die("ERROR: DEFAULT_THEME is not properly set");
	}

	@$nocontent = (isset($_GET['nocontent'])) ? $_GET['nocontent'] : $_POST['nocontent'];

	require("inc/adminCheck.php");
	if ($nocontent) {
		require("inc/content.php");
	} else {

		if ($cat == "admin" && !$is_admin) {
			header('WWW-Authenticate: Basic realm="' . $siteTitle . '"');
			header('HTTP/1.0 401 Unauthorized');
			require("inc/auth-required.php");
			die;
		}


		$template->loadTemplateFromFile("$localPath/themes/$siteTheme/index.html");

		if (file_exists("$localPath/themes/$siteTheme/config.php")) {
			require("$localPath/themes/$siteTheme/config.php");
		}

		$template->parseTemplate();

		unset($data, $pageContent);
	}
?>
