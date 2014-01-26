<?
	if (!defined("VALID")) die;

	$template = new template();


	$template->addTagInclude("content", 	"$localPath/inc/content.php");
	$template->addTagInclude("head",			"inc/head.php");

	$template->addTagInclude("box_links",	"inc/box_links.php");
	$template->addTagInclude("box_links_dropdown",	"inc/box_links_dropdown.php");

	$template->addTagInclude("box_index",				"inc/box_index.php");
	$template->addTagInclude("box_index_dropdown",	"inc/box_index_dropdown.php");

	$template->addTagInclude("box_login",	"inc/box_login.php");
	$template->addTagInclude("box_blurb",	"inc/box_blurb.php");
	$template->addTagInclude("box_search",	"inc/box_search.php");
	$template->addTagInclude("box_announcements",	"inc/box_announcements.php");

	$template->addTagInclude("site_heading",	"inc/heading.php");

	$template->addTagInclude("site_footer",	"inc/footer.php");

	$template->addTagVariable("site_title",	"siteTitle");
	$template->addTagVariable("site_title2",	"siteTitle2");

	$template->addTagVariable("urlPrefix",		"urlPrefix");
	$template->addTagVariable("siteTheme",		"siteTheme");

	$template->addTagFunction("site_location",	"echoLocation");

	$template->addTagDefine("site_url", SERVER_URL);

	$template->addTagVariable("site_email",	"contact_email");
	$template->addTagVariable("site_aim",		"contact_aim");
	$template->addTagInclude("login_heading",	"inc/loginHeading.php");
	$template->addTagReturnFunction("fortune",	"fortune");
	$template->addTagReturnFunction("current_date", "current_date");

	$template->addTagInclude("cotd",	"inc/cotd.php");

	$template->addTagInclude("section_logo",	"inc/section_logo.php");

	$template->addTagVariable("section_name",	"cat");

	$template->addTagInclude("current_song",	"inc/song.php");


?>
