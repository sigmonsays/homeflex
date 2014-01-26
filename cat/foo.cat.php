<H2>Foo</H2>

Testing ground here.... 

<?
	class moo {

		var $obj;

		/* constructor */
		function moo(&$obj) {
			
		}
	}



	if ($_GET['create'] == 1) {
		$pic = new picture($dbi, 369);
		echo "<a href=\"$urlPrefix/foo?create=0\">Don't Create Picture Object</a><br>\n";
	} else {
		echo "<a href=\"$urlPrefix/foo?create=1\">Create Picture Object</a><br>\n";
	}

	$queries = $dbi->get_queries();

	echo "<pre>\n";
	print_r($queries);
	echo "</pre>\n";

?>
