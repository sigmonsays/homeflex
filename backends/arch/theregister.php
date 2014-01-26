<?
/*
//	theregister.php3
//
//	Version:	2.0.4
//
//	Author:		Kalle Kiviaho - kivi@chl.chalmers.se
//	Lastmod:	2000-09-24
//	Homepage:	http://swamp.chl.chalmers.se/backends/
//
//	This is an PHP include of SSI file
//
//	PHP:
//	<?
//	include("theregister.php3");
//	?>
//
//	SSI:
//	<!--#include virtual="theregister.php3" -->
//
//	Feel free to modify the code and e-mail me fixes as you see
//	them fit...
//
*/

//	Customize as you like it

$link_prefix	=	"- ";
$link_postfix	=	"<BR>\n";
$cache_file	=	"/tmp/theregister.co.uk.cache";
$cache_time	=	3600;
$max_items	=	10;
$target		=	"_top";

//	End of customizations

$backend	=	"http://www.theregister.co.uk/tonys/slashdot.rdf";

$items		=	0;
$time		=	split(" ", microtime());

srand((double)microtime()*1000000);
$cache_time_rnd	=	300 - rand(0, 600);

if ( (!(file_exists($cache_file))) || ((filectime($cache_file) + $cache_time - $time[1]) + $cache_time_rnd < 0) || (!(filesize($cache_file))) ) {

	$fpread = @fopen($backend, 'r');
	if(!$fpread) {

	} else {

		$fpwrite = fopen($cache_file, 'w');
		if(!$fpwrite) {
			echo "$errstr ($errno)<br>\n";

		} else {

			while(! feof($fpread) ) {

				$buffer = ltrim(Chop(fgets($fpread, 256)));

				if (($buffer == "<item>") && ($items < $max_items)) {
					$title = ltrim(Chop(fgets($fpread, 256)));
					$slask = ltrim(Chop(fgets($fpread, 256)));
					$url = ltrim(Chop(fgets($fpread, 256)));

					$title = ereg_replace( "<title>", "", $title );
					$title = ereg_replace( "</title>", "", $title );
					$url = ereg_replace( "<link>", "", $url );
					$url = ereg_replace( "</link>", "", $url );
					$url = ereg_replace( "</item>", "", $url );

					fputs($fpwrite, "$link_prefix<A HREF=\"$url\" TARGET=\"$target\">$title</A>$link_postfix");

					$items++;
				}


			}
		}
		fclose($fpread);
	}
	@fclose($fpwrite);
}
if (file_exists($cache_file)) {
	include($cache_file);
}
?>
