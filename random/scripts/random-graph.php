<?

	function graph_array(&$pic, $width, $height, $ary, $fg) {
		@$inc = ($width / count($ary));
		for($i=1; $i < (count($ary) - 1); $i++) {
			$x = $i * $inc;
			ImageLine($pic, $x, $height - $ary[$i], $x + $inc, $height - $ary[$i + 1], $fg);
		}
	}



	header ("Content-type: image/png");

	$width = 260;
	$height = 50;
	$grid = 10;
	$title = 1;

	$fgcolor = array(0, 0, 0);
	$bgcolor = array(233, 233, 223);
	$gridca = array(192, 192, 192);

	$img = ImageCreate($width, $height);
	$bg = ImageColorAllocate($img, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
	$fg = ImageColorAllocate($img, $fgcolor[0], $fgcolor[1], $fgcolor[2]);
	$gridc = ImageColorAllocate($img, $gridca[0], $gridca[1], $gridca[2]);
	$red = ImageColorAllocate($img, 255, 0, 0);

	if ($grid) {
		for($i=0; $i<$width; $i += $grid)
			ImageLine ($img, $i, 0, $i, $height, $gridc);
		for($i=0; $i<$height; $i += $grid)
			ImageLine ($img, 0, $i, $width, $i, $gridc);
	}

//begin testing

		$results = array();
		srand(microtime() * 1000000);
		for($r=0; $r<100; $r++)
			$results[] = rand(0, $height - 30);
		graph_array($img, $width, $height, $results, $fg);

//end testing!

	if ($title) {

		$msg = "Random Graph of the day";

		$fw = ImageFontWidth(3) * strlen($msg) + 5;
		$fh = ImageFontHeight(3) + 10;

		ImageFilledRectangle($img, 3, 3, $fw, $fh, $bg);
		ImageRectangle($img, 3, 3, $fw, $fh, $gridc);
		ImageString ($img, 3, 5, 7, $msg, $fg);

		ImageLine ($img, 1, 1, $width, 1, $fg);
		ImageLine ($img, 1, 1, 1, $height, $fg);
		ImageLine ($img, $width - 1, 0, $width - 1, $height, $fg);
		ImageLine ($img, 1, $height - 1, $width, $height - 1, $fg);
	}


	ImagePNG ($img);
	ImageDestroy($img);

?>
