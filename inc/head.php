<?
	if (!defined("VALID")) die;

	require("$localPath/inc/global.css");

	if (file_exists("$localPath/themes/$siteTheme/style.css"))
		require("$localPath/themes/$siteTheme/style.css");
?>
