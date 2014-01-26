<?
/*
//	freshmeat.php3
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
//	include("freshmeat.php3");
//	?>
//
//	SSI:
//	<!--#include virtual="freshmeat.php3" -->
//
//	Feel free to modify the code and e-mail me fixes as you see
//	them fit...
//
*/

//	Customize as you like it

$link_prefix	=	"- ";
$link_postfix	=	"<BR>\n";
$cache_file	=	"/tmp/freshmeat.net.cache";
$cache_time	=	3600;
$max_items	=	10;
$target		=	"_top";

//	End of customizations

$backend	=	"http://freshmeat.net/backend/fm.rdf";

$items		=	0;
$time		=	split(" ", microtime());

srand((double)microtime()*1000000);
$cache_time_rnd	=	300 - rand(0, 600);

if ( (!(file_exists($cache_file))) || ((filectime($cache_file) + $cache_time - $time[1]) + $cache_time_rnd < 0) || (!(filesize($cache_file))) ) {

	//check to see if the server is up.
	$abort = 0;
	@$sck = fsockopen("freshmeat.net", 80, $errmsg, $errno, 10);
	if (!$sck) $abort=1;
	@fclose($sck);
	if ($abort) {
		echo "Is is possible that <a href=\"http://www.freshmeat.net/\">Freshmeat.net</a> is down!? ... <b>Below is an older backend</b><br>";
	} else {
		$fpread = fopen($backend, 'r');
		if(!$fpread) {
	//		echo "$errstr ($errno)<br>\n";
	//		exit;
		} else {

			$fpwrite = fopen($cache_file, 'w');
			if(!$fpwrite) {
//				echo "$errstr ($errno)<br>\n";
//				exit;
			} else {

				while(! feof($fpread) ) {

					$buffer = ltrim(Chop(fgets($fpread, 256)));

					if (($buffer == "<item>") && ($items < $max_items)) {
						$title = ltrim(Chop(fgets($fpread, 256)));
						$link = ltrim(Chop(fgets($fpread, 256)));

						$title = ereg_replace( "<title>", "", $title );
						$title = ereg_replace( "</title>", "", $title );
						$link = ereg_replace( "<link>", "", $link );
						$link = ereg_replace( "</link>", "", $link );

						fputs($fpwrite, "$link_prefix<A HREF=\"$link\" TARGET=\"$target\">$title</A>$link_postfix");

						$items++;
					}
				}
			}
			fclose($fpread);
		}
		fclose($fpwrite);
	}
} //end check to see if server is up.

if (file_exists($cache_file)) {
	include($cache_file);
}
?>
