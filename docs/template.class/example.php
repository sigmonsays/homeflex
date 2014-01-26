<?
/*

Possible contents of template.txt
-----------------
<HTML>
<TITLE><%templ tag(site_title) $></TITLE>

<%templ tag(head) %>
Current Date: <%templ date(F dS, Y) %><BR>
Current Location: <%templ tag(site_location) %><BR>
<P>

<H2>Fortune</H2>
<%templ
	echo(Your fortune);
	tag(fortune);
	echo(<BR>);
%>

</HTML>
-----------------
*/
	$template = new template();

	/* Setup some tags */
	$template->addTagInclude("head",			"inc/head.inc");
	$template->addTagVariable("site_title",	"siteTitle");
	$template->addTagFunction("site_location",	"echoLocation");
	$template->addTagDefine("site_url", SERVER_URL);
	$template->addTagReturnFunction("fortune",	"fortune");


	/* now load the template and display it */

	$template->loadTemplateFromFile("template.txt");

	$template->parseTemplate();

?>
