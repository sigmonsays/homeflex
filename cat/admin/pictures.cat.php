<?
	if (!defined("VALID")) die;

	switch ($arg1) {

		case "color": // pick color javascript shit used in transform
			if (!$nocontent) break;

			echo "<html>\n";
			echo "<title>$siteTitle - Pick Color..</title>\n";
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function showColor(val) {
 // document.opener.colorForm.hexval.value = val;

	r = parseInt( val.substring(1, 3), 16);
	g = parseInt( val.substring(3, 5), 16);
	b = parseInt( val.substring(5, 7), 16);

	window.opener.document.colorForm.red.value = r;
	window.opener.document.colorForm.green.value = g;
	window.opener.document.colorForm.blue.value = b;
}
//  End -->
</script>
</HEAD>

<center>
<form name=colorForm>
<map name="colmap">
<area shape="rect" coords="1,1,7,10" href="javascript:showColor('#00FF00')">
<area shape="rect" coords="9,1,15,10" href="javascript:showColor('#00FF33')">
<area shape="rect" coords="17,1,23,10" href="javascript:showColor('#00FF66')">
<area shape="rect" coords="25,1,31,10" href="javascript:showColor('#00FF99')">
<area shape="rect" coords="33,1,39,10" href="javascript:showColor('#00FFCC')">
<area shape="rect" coords="41,1,47,10" href="javascript:showColor('#00FFFF')">
<area shape="rect" coords="49,1,55,10" href="javascript:showColor('#33FF00')">
<area shape="rect" coords="57,1,63,10" href="javascript:showColor('#33FF33')">
<area shape="rect" coords="65,1,71,10" href="javascript:showColor('#33FF66')">
<area shape="rect" coords="73,1,79,10" href="javascript:showColor('#33FF99')">
<area shape="rect" coords="81,1,87,10" href="javascript:showColor('#33FFCC')">
<area shape="rect" coords="89,1,95,10" href="javascript:showColor('#33FFFF')">
<area shape="rect" coords="97,1,103,10" href="javascript:showColor('#66FF00')">
<area shape="rect" coords="105,1,111,10" href="javascript:showColor('#66FF33')">
<area shape="rect" coords="113,1,119,10" href="javascript:showColor('#66FF66')">
<area shape="rect" coords="121,1,127,10" href="javascript:showColor('#66FF99')">
<area shape="rect" coords="129,1,135,10" href="javascript:showColor('#66FFCC')">
<area shape="rect" coords="137,1,143,10" href="javascript:showColor('#66FFFF')">
<area shape="rect" coords="145,1,151,10" href="javascript:showColor('#99FF00')">
<area shape="rect" coords="153,1,159,10" href="javascript:showColor('#99FF33')">
<area shape="rect" coords="161,1,167,10" href="javascript:showColor('#99FF66')">
<area shape="rect" coords="169,1,175,10" href="javascript:showColor('#99FF99')">
<area shape="rect" coords="177,1,183,10" href="javascript:showColor('#99FFCC')">
<area shape="rect" coords="185,1,191,10" href="javascript:showColor('#99FFFF')">
<area shape="rect" coords="193,1,199,10" href="javascript:showColor('#CCFF00')">
<area shape="rect" coords="201,1,207,10" href="javascript:showColor('#CCFF33')">
<area shape="rect" coords="209,1,215,10" href="javascript:showColor('#CCFF66')">
<area shape="rect" coords="217,1,223,10" href="javascript:showColor('#CCFF99')">
<area shape="rect" coords="225,1,231,10" href="javascript:showColor('#CCFFCC')">
<area shape="rect" coords="233,1,239,10" href="javascript:showColor('#CCFFFF')">
<area shape="rect" coords="241,1,247,10" href="javascript:showColor('#FFFF00')">
<area shape="rect" coords="249,1,255,10" href="javascript:showColor('#FFFF33')">
<area shape="rect" coords="257,1,263,10" href="javascript:showColor('#FFFF66')">
<area shape="rect" coords="265,1,271,10" href="javascript:showColor('#FFFF99')">
<area shape="rect" coords="273,1,279,10" href="javascript:showColor('#FFFFCC')">
<area shape="rect" coords="281,1,287,10" href="javascript:showColor('#FFFFFF')">
<area shape="rect" coords="1,12,7,21" href="javascript:showColor('#00CC00')">
<area shape="rect" coords="9,12,15,21" href="javascript:showColor('#00CC33')">
<area shape="rect" coords="17,12,23,21" href="javascript:showColor('#00CC66')">
<area shape="rect" coords="25,12,31,21" href="javascript:showColor('#00CC99')">
<area shape="rect" coords="33,12,39,21" href="javascript:showColor('#00CCCC')">
<area shape="rect" coords="41,12,47,21" href="javascript:showColor('#00CCFF')">
<area shape="rect" coords="49,12,55,21" href="javascript:showColor('#33CC00')">
<area shape="rect" coords="57,12,63,21" href="javascript:showColor('#33CC33')">
<area shape="rect" coords="65,12,71,21" href="javascript:showColor('#33CC66')">
<area shape="rect" coords="73,12,79,21" href="javascript:showColor('#33CC99')">
<area shape="rect" coords="81,12,87,21" href="javascript:showColor('#33CCCC')">
<area shape="rect" coords="89,12,95,21" href="javascript:showColor('#33CCFF')">
<area shape="rect" coords="97,12,103,21" href="javascript:showColor('#66CC00')">
<area shape="rect" coords="105,12,111,21" href="javascript:showColor('#66CC33')">
<area shape="rect" coords="113,12,119,21" href="javascript:showColor('#66CC66')">
<area shape="rect" coords="121,12,127,21" href="javascript:showColor('#66CC99')">
<area shape="rect" coords="129,12,135,21" href="javascript:showColor('#66CCCC')">
<area shape="rect" coords="137,12,143,21" href="javascript:showColor('#66CCFF')">
<area shape="rect" coords="145,12,151,21" href="javascript:showColor('#99CC00')">
<area shape="rect" coords="153,12,159,21" href="javascript:showColor('#99CC33')">
<area shape="rect" coords="161,12,167,21" href="javascript:showColor('#99CC66')">
<area shape="rect" coords="169,12,175,21" href="javascript:showColor('#99CC99')">
<area shape="rect" coords="177,12,183,21" href="javascript:showColor('#99CCCC')">
<area shape="rect" coords="185,12,191,21" href="javascript:showColor('#99CCFF')">
<area shape="rect" coords="193,12,199,21" href="javascript:showColor('#CCCC00')">
<area shape="rect" coords="201,12,207,21" href="javascript:showColor('#CCCC33')">
<area shape="rect" coords="209,12,215,21" href="javascript:showColor('#CCCC66')">
<area shape="rect" coords="217,12,223,21" href="javascript:showColor('#CCCC99')">
<area shape="rect" coords="225,12,231,21" href="javascript:showColor('#CCCCCC')">
<area shape="rect" coords="233,12,239,21" href="javascript:showColor('#CCCCFF')">
<area shape="rect" coords="241,12,247,21" href="javascript:showColor('#FFCC00')">
<area shape="rect" coords="249,12,255,21" href="javascript:showColor('#FFCC33')">
<area shape="rect" coords="257,12,263,21" href="javascript:showColor('#FFCC66')">
<area shape="rect" coords="265,12,271,21" href="javascript:showColor('#FFCC99')">
<area shape="rect" coords="273,12,279,21" href="javascript:showColor('#FFCCCC')">
<area shape="rect" coords="281,12,287,21" href="javascript:showColor('#FFCCFF')">
<area shape="rect" coords="1,23,7,32" href="javascript:showColor('#009900')">
<area shape="rect" coords="9,23,15,32" href="javascript:showColor('#009933')">
<area shape="rect" coords="17,23,23,32" href="javascript:showColor('#009966')">
<area shape="rect" coords="25,23,31,32" href="javascript:showColor('#009999')">
<area shape="rect" coords="33,23,39,32" href="javascript:showColor('#0099CC')">
<area shape="rect" coords="41,23,47,32" href="javascript:showColor('#0099FF')">
<area shape="rect" coords="49,23,55,32" href="javascript:showColor('#339900')">
<area shape="rect" coords="57,23,63,32" href="javascript:showColor('#339933')">
<area shape="rect" coords="65,23,71,32" href="javascript:showColor('#339966')">
<area shape="rect" coords="73,23,79,32" href="javascript:showColor('#339999')">
<area shape="rect" coords="81,23,87,32" href="javascript:showColor('#3399CC')">
<area shape="rect" coords="89,23,95,32" href="javascript:showColor('#3399FF')">
<area shape="rect" coords="97,23,103,32" href="javascript:showColor('#669900')">
<area shape="rect" coords="105,23,111,32" href="javascript:showColor('#669933')">
<area shape="rect" coords="113,23,119,32" href="javascript:showColor('#669966')">
<area shape="rect" coords="121,23,127,32" href="javascript:showColor('#669999')">
<area shape="rect" coords="129,23,135,32" href="javascript:showColor('#6699CC')">
<area shape="rect" coords="137,23,143,32" href="javascript:showColor('#6699FF')">
<area shape="rect" coords="145,23,151,32" href="javascript:showColor('#999900')">
<area shape="rect" coords="153,23,159,32" href="javascript:showColor('#999933')">
<area shape="rect" coords="161,23,167,32" href="javascript:showColor('#999966')">
<area shape="rect" coords="169,23,175,32" href="javascript:showColor('#999999')">
<area shape="rect" coords="177,23,183,32" href="javascript:showColor('#9999CC')">
<area shape="rect" coords="185,23,191,32" href="javascript:showColor('#9999FF')">
<area shape="rect" coords="193,23,199,32" href="javascript:showColor('#CC9900')">
<area shape="rect" coords="201,23,207,32" href="javascript:showColor('#CC9933')">
<area shape="rect" coords="209,23,215,32" href="javascript:showColor('#CC9966')">
<area shape="rect" coords="217,23,223,32" href="javascript:showColor('#CC9999')">
<area shape="rect" coords="225,23,231,32" href="javascript:showColor('#CC99CC')">
<area shape="rect" coords="233,23,239,32" href="javascript:showColor('#CC99FF')">
<area shape="rect" coords="241,23,247,32" href="javascript:showColor('#FF9900')">
<area shape="rect" coords="249,23,255,32" href="javascript:showColor('#FF9933')">
<area shape="rect" coords="257,23,263,32" href="javascript:showColor('#FF9966')">
<area shape="rect" coords="265,23,271,32" href="javascript:showColor('#FF9999')">
<area shape="rect" coords="273,23,279,32" href="javascript:showColor('#FF99CC')">
<area shape="rect" coords="281,23,287,32" href="javascript:showColor('#FF99FF')">
<area shape="rect" coords="1,34,7,43" href="javascript:showColor('#006600')">
<area shape="rect" coords="9,34,15,43" href="javascript:showColor('#006633')">
<area shape="rect" coords="17,34,23,43" href="javascript:showColor('#006666')">
<area shape="rect" coords="25,34,31,43" href="javascript:showColor('#006699')">
<area shape="rect" coords="33,34,39,43" href="javascript:showColor('#0066CC')">
<area shape="rect" coords="41,34,47,43" href="javascript:showColor('#0066FF')">
<area shape="rect" coords="49,34,55,43" href="javascript:showColor('#336600')">
<area shape="rect" coords="57,34,63,43" href="javascript:showColor('#336633')">
<area shape="rect" coords="65,34,71,43" href="javascript:showColor('#336666')">
<area shape="rect" coords="73,34,79,43" href="javascript:showColor('#336699')">
<area shape="rect" coords="81,34,87,43" href="javascript:showColor('#3366CC')">
<area shape="rect" coords="89,34,95,43" href="javascript:showColor('#3366FF')">
<area shape="rect" coords="97,34,103,43" href="javascript:showColor('#666600')">
<area shape="rect" coords="105,34,111,43" href="javascript:showColor('#666633')">
<area shape="rect" coords="113,34,119,43" href="javascript:showColor('#666666')">
<area shape="rect" coords="121,34,127,43" href="javascript:showColor('#666699')">
<area shape="rect" coords="129,34,135,43" href="javascript:showColor('#6666CC')">
<area shape="rect" coords="137,34,143,43" href="javascript:showColor('#6666FF')">
<area shape="rect" coords="145,34,151,43" href="javascript:showColor('#996600')">
<area shape="rect" coords="153,34,159,43" href="javascript:showColor('#996633')">
<area shape="rect" coords="161,34,167,43" href="javascript:showColor('#996666')">
<area shape="rect" coords="169,34,175,43" href="javascript:showColor('#996699')">
<area shape="rect" coords="177,34,183,43" href="javascript:showColor('#9966CC')">
<area shape="rect" coords="185,34,191,43" href="javascript:showColor('#9966FF')">
<area shape="rect" coords="193,34,199,43" href="javascript:showColor('#CC6600')">
<area shape="rect" coords="201,34,207,43" href="javascript:showColor('#CC6633')">
<area shape="rect" coords="209,34,215,43" href="javascript:showColor('#CC6666')">
<area shape="rect" coords="217,34,223,43" href="javascript:showColor('#CC6699')">
<area shape="rect" coords="225,34,231,43" href="javascript:showColor('#CC66CC')">
<area shape="rect" coords="233,34,239,43" href="javascript:showColor('#CC66FF')">
<area shape="rect" coords="241,34,247,43" href="javascript:showColor('#FF6600')">
<area shape="rect" coords="249,34,255,43" href="javascript:showColor('#FF6633')">
<area shape="rect" coords="257,34,263,43" href="javascript:showColor('#FF6666')">
<area shape="rect" coords="265,34,271,43" href="javascript:showColor('#FF6699')">
<area shape="rect" coords="273,34,279,43" href="javascript:showColor('#FF66CC')">
<area shape="rect" coords="281,34,287,43" href="javascript:showColor('#FF66FF')">
<area shape="rect" coords="1,45,7,54" href="javascript:showColor('#003300')">
<area shape="rect" coords="9,45,15,54" href="javascript:showColor('#003333')">
<area shape="rect" coords="17,45,23,54" href="javascript:showColor('#003366')">
<area shape="rect" coords="25,45,31,54" href="javascript:showColor('#003399')">
<area shape="rect" coords="33,45,39,54" href="javascript:showColor('#0033CC')">
<area shape="rect" coords="41,45,47,54" href="javascript:showColor('#0033FF')">
<area shape="rect" coords="49,45,55,54" href="javascript:showColor('#333300')">
<area shape="rect" coords="57,45,63,54" href="javascript:showColor('#333333')">
<area shape="rect" coords="65,45,71,54" href="javascript:showColor('#333366')">
<area shape="rect" coords="73,45,79,54" href="javascript:showColor('#333399')">
<area shape="rect" coords="81,45,87,54" href="javascript:showColor('#3333CC')">
<area shape="rect" coords="89,45,95,54" href="javascript:showColor('#3333FF')">
<area shape="rect" coords="97,45,103,54" href="javascript:showColor('#663300')">
<area shape="rect" coords="105,45,111,54" href="javascript:showColor('#663333')">
<area shape="rect" coords="113,45,119,54" href="javascript:showColor('#663366')">
<area shape="rect" coords="121,45,127,54" href="javascript:showColor('#663399')">
<area shape="rect" coords="129,45,135,54" href="javascript:showColor('#6633CC')">
<area shape="rect" coords="137,45,143,54" href="javascript:showColor('#6633FF')">
<area shape="rect" coords="145,45,151,54" href="javascript:showColor('#993300')">
<area shape="rect" coords="153,45,159,54" href="javascript:showColor('#993333')">
<area shape="rect" coords="161,45,167,54" href="javascript:showColor('#993366')">
<area shape="rect" coords="169,45,175,54" href="javascript:showColor('#993399')">
<area shape="rect" coords="177,45,183,54" href="javascript:showColor('#9933CC')">
<area shape="rect" coords="185,45,191,54" href="javascript:showColor('#9933FF')">
<area shape="rect" coords="193,45,199,54" href="javascript:showColor('#CC3300')">
<area shape="rect" coords="201,45,207,54" href="javascript:showColor('#CC3333')">
<area shape="rect" coords="209,45,215,54" href="javascript:showColor('#CC3366')">
<area shape="rect" coords="217,45,223,54" href="javascript:showColor('#CC3399')">
<area shape="rect" coords="225,45,231,54" href="javascript:showColor('#CC33CC')">
<area shape="rect" coords="233,45,239,54" href="javascript:showColor('#CC33FF')">
<area shape="rect" coords="241,45,247,54" href="javascript:showColor('#FF3300')">
<area shape="rect" coords="249,45,255,54" href="javascript:showColor('#FF3333')">
<area shape="rect" coords="257,45,263,54" href="javascript:showColor('#FF3366')">
<area shape="rect" coords="265,45,271,54" href="javascript:showColor('#FF3399')">
<area shape="rect" coords="273,45,279,54" href="javascript:showColor('#FF33CC')">
<area shape="rect" coords="281,45,287,54" href="javascript:showColor('#FF33FF')">
<area shape="rect" coords="1,56,7,65" href="javascript:showColor('#000000')">
<area shape="rect" coords="9,56,15,65" href="javascript:showColor('#000033')">
<area shape="rect" coords="17,56,23,65" href="javascript:showColor('#000066')">
<area shape="rect" coords="25,56,31,65" href="javascript:showColor('#000099')">
<area shape="rect" coords="33,56,39,65" href="javascript:showColor('#0000CC')">
<area shape="rect" coords="41,56,47,65" href="javascript:showColor('#0000FF')">
<area shape="rect" coords="49,56,55,65" href="javascript:showColor('#330000')">
<area shape="rect" coords="57,56,63,65" href="javascript:showColor('#330033')">
<area shape="rect" coords="65,56,71,65" href="javascript:showColor('#330066')">
<area shape="rect" coords="73,56,79,65" href="javascript:showColor('#330099')">
<area shape="rect" coords="81,56,87,65" href="javascript:showColor('#3300CC')">
<area shape="rect" coords="89,56,95,65" href="javascript:showColor('#3300FF')">
<area shape="rect" coords="97,56,103,65" href="javascript:showColor('#660000')">
<area shape="rect" coords="105,56,111,65" href="javascript:showColor('#660033')">
<area shape="rect" coords="113,56,119,65" href="javascript:showColor('#660066')">
<area shape="rect" coords="121,56,127,65" href="javascript:showColor('#660099')">
<area shape="rect" coords="129,56,135,65" href="javascript:showColor('#6600CC')">
<area shape="rect" coords="137,56,143,65" href="javascript:showColor('#6600FF')">
<area shape="rect" coords="145,56,151,65" href="javascript:showColor('#990000')">
<area shape="rect" coords="153,56,159,65" href="javascript:showColor('#990033')">
<area shape="rect" coords="161,56,167,65" href="javascript:showColor('#990066')">
<area shape="rect" coords="169,56,175,65" href="javascript:showColor('#990099')">
<area shape="rect" coords="177,56,183,65" href="javascript:showColor('#9900CC')">
<area shape="rect" coords="185,56,191,65" href="javascript:showColor('#9900FF')">
<area shape="rect" coords="193,56,199,65" href="javascript:showColor('#CC0000')">
<area shape="rect" coords="201,56,207,65" href="javascript:showColor('#CC0033')">
<area shape="rect" coords="209,56,215,65" href="javascript:showColor('#CC0066')">
<area shape="rect" coords="217,56,223,65" href="javascript:showColor('#CC0099')">
<area shape="rect" coords="225,56,231,65" href="javascript:showColor('#CC00CC')">
<area shape="rect" coords="233,56,239,65" href="javascript:showColor('#CC00FF')">
<area shape="rect" coords="241,56,247,65" href="javascript:showColor('#FF0000')">
<area shape="rect" coords="249,56,255,65" href="javascript:showColor('#FF0033')">
<area shape="rect" coords="257,56,263,65" href="javascript:showColor('#FF0066')">
<area shape="rect" coords="265,56,271,65" href="javascript:showColor('#FF0099')">
<area shape="rect" coords="273,56,279,65" href="javascript:showColor('#FF00CC')">
<area shape="rect" coords="281,56,287,65" href="javascript:showColor('#FF00FF')">
</map>
<a><img usemap="#colmap" src="<?= IMAGES_URL_PREFIX . "/colortable.gif" ?>" border=0 width=289 height=67></a><br>
</form>
<input type=button onClick="window.close(-1);" value="Close">
</center>
<?

			echo "</html>\n";

			break;

		case "transform": // transform image

			$id = intval($arg2);
			$node = intval($_GET['node']);


			$picture = new picture($dbi, $id);

			if (!$picture->id) {
				echo "<b>ERROR</b>: Invalid picture ID passed<br>\n";
				unset($arg1);
				require(__FILE__);
				break;
			}

			$file = PICTURES_LOCAL_PATH . "/" . $picture->picture;

			$pict = new picture_transform($file);

			if (!$pict->image) {
				echo "<b>ERROR</b>: Invalid picture<br>\n";
				unset($arg1);
				require(__FILE__);
				break;
			}

			if ($arg3 == 'thumbnail') {
				$picture->generateThumbnail();
				echo "Regenerated thumbnail<br>\n";

			} else if ($arg3 == 'string') {
				$font = intval($_GET['font']);
				$text = $_GET['text'];
				$x = intval($_GET['x']);
				$y = intval($_GET['y']);
				$pict->string($font, $x, $y, $text);
				echo "Added text '" . htmlspecialchars($text) . "'<br>\n";
				$pict->save();

			} else if ($arg3 == 'rotate') {
				$d = intval($_GET['d']);
				$pict->rotate($d);
				echo "Rotated image $d degrees<br>\n";
				$pict->save();

			} else if ($arg3 == 'text') {

				$x = intval($_GET['x']);
				$y = intval($_GET['y']);
				$size = intval($_GET['size']);
				$text = $_GET['text'];
				$angle = intval($_GET['angle']);
				$color = array( intval($_GET['red']), intval($_GET['green']), intval($_GET['blue']) );

				$fontfilename = basename($_GET['fontfilename']);
				$fontfile = FONTS_TTF_LOCAL_PATH . "/" . $fontfilename;

				$pict->text($size, $angle, $x, $y, $color, $fontfile, $text);

				$pict->save();
			}

			echo "<h2>Transform " . htmlspecialchars(stripslashes($picture->name)) . "</h2>\n";

			echo "<li><a href=\"$urlPrefix/admin/pictures/transform/$id?node=$node\">Reload</a></li>\n";
			echo "<li><a href=\"$urlPrefix/admin/pictures?node=$node\">Back to category</a></li><br>\n";

			echo "Dimensions: " . $pict->width . "x" . $pict->height . "<br>\n";

			echo "<br>\n";


			$f = new formInput;

			?>
			<script language="javascript">
				function pickColor() {
					window.open("<?= "$urlPrefix/admin/pictures/color?nocontent=1" ?>",
					"pickColor",
					"width=400,height=120,fullscreen=no,toolbar=no,status=no,menubar=no,scrollbars=no,resizable=yes,directories=no,location=no");
				}
			</script>
			<?

			echo "<table width=100% border=0 align=center>\n";

			$img_src = PICTURES_URL_PREFIX . "/" . $picture->picture;

			list($width, $height) = $pict->aspectResizeDimensions(200);
			echo "<tr>\n";

				// preview image here
				echo "<td valign=top width=1>";
					echo "<a target=\"_NEW\" href=\"$img_src\"><img style=\"border: 1px solid black;\" border=0 width=$width height=$height src=\"$img_src\"></a>";
				echo "</td>\n";

				// spacer
				echo "<td width=20>&nbsp;</td>\n";

				// <!-- start image edit cell
				echo "<td valign=top>\n";

				echo "<div>\n";
				echo "<li><a href=\"$urlPrefix/admin/pictures/transform/$id/thumbnail?node=$node\">Regenerate Thumbnail</a></li>\n";
				echo "</div>\n";
				echo "<br>\n";

				// add string form
				echo "<div><b>Add String</b><br>\n";
				
				$f->start("$urlPrefix/admin/pictures/transform/$id/string", NULL, "GET");
				$f->hidden("node", $node);
				echo "<table width=100% border=0 align=center>\n";

				echo "<tr>\n";
					echo "<td>Font</td>\n";
					echo "<td>\n";
					$font_ar = array(1 => "Very Small", 2 => "Small", 3 => "Medium", 4 => "Large", 5 => "Very Large");
					$f->select("font", $font_ar);
					echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
					echo "<td>Position</td>\n";
					echo "<td>\n";
						$f->text("x", 0, 2); echo "x "; $f->text("y", 0, 2);
					echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
					echo "<td>Text</td>\n";
					echo "<td>\n"; $f->text("text"); echo "</td>\n";
				echo "</tr>\n";

				echo "<tr><td colspan=2>"; $f->submit("OK"); echo "</tr>\n";
				echo "</table>\n";
				$f->end();
				echo "</div>\n";


				// font text form
				echo "<div><b>Font Text</b><br>\n";
				
				$f->name = "colorForm";
				$f->start("$urlPrefix/admin/pictures/transform/$id/text", NULL, "GET");
				$f->name = NULL;

				$f->hidden("node", $node);
				echo "<table width=100% border=0 align=center>\n";

				echo "<tr>\n";
					echo "<td>Color</td>\n";
					echo "<td>\n";
						$f->text("red", 0, 3); $f->text("green", 0, 3); $f->text("blue", 0, 3);
						echo " &nbsp; <input type=\"button\" onClick=\"pickColor();\" value=\"Color..\">";
					echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
					echo "<td>Font</td>\n";
					echo "<td>\n";
					$fonts = array();

					$fso = new fso;
					$ar = $fso->loadDirectory(FONTS_TTF_LOCAL_PATH);
					foreach($ar['file'] as $font) {
						$fonts[$font] = $font;
					}
					$f->select("fontfilename", $fonts);
					echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
					echo "<td>Font Size</td>\n";
					echo "<td>\n"; $f->text("size", 10, 2); echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
					echo "<td>Font Angle</td>\n";
					echo "<td>\n";
						$f->radio("d", 0);		echo "0 ";
						$f->radio("d", 90);		echo "90 "; 
						$f->radio("d", 180);		echo "180 ";
						$f->radio("d", 270);		echo "270 "; 
					echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
					echo "<td>Position</td>\n";
					echo "<td>\n";
						$f->text("x", 0, 2); echo "x "; $f->text("y", 0, 2);
					echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
					echo "<td>Text</td>\n";
					echo "<td>\n"; $f->text("text"); echo "</td>\n";
				echo "</tr>\n";

				echo "<tr><td colspan=2>"; $f->submit("OK"); echo "</tr>\n";
				echo "</table>\n";
				$f->end();
				echo "</div>\n";



				// image rotate form
				echo "<div><b>Rotate</b><br>\n";
				$f->start("$urlPrefix/admin/pictures/transform/$id/rotate", NULL, "GET");
				$f->hidden("node", $node);

				$f->radio("d", 90);		echo "90 "; 
				$f->radio("d", 180);		echo "180 ";
				$f->radio("d", 270);		echo "270 "; 
				echo " &nbsp; ";
				$f->submit("OK");
				$f->end();
				echo "</div>\n";



				echo "</td>\n";
				// End image edit cell --->

			echo "</tr>\n";

			echo "</table>\n";

			break;

		case "move":

				if ($arg2 == 'submit') {

					$type = $_POST['type'];
					$to = intval($_POST['to']);
					$id = intval($_POST['id']);

					if ($to == $id) {
						echo "<b>ERROR</b>: Can not move onto itself<br>\n";
						unset($arg1);
						$_GET['node'] = $_POST['node'];
						require(__FILE__);
						break;
					}

					if ($type == 'picture') {
						$qry = "UPDATE ${dbTables['pictures']} SET category='$to' WHERE id='$id'";

					} else { // default to category
						$qry = "UPDATE ${dbTables['pictures_cat']} SET parent='$to' WHERE id='$id'";
					}

					if (!$dbi->db->query($qry)) {
						echo "<b>ERROR</b>: Unable to make the move..<br>\n";
					}

					unset($arg1);
					$_GET['node'] = $_POST['node'];
					require(__FILE__);
						
				} else {	
					$id = intval($_GET['id']);
					$node = intval($_GET['node']);
					$to = intval($_GET['to']);
					$type = $_GET['type'];

					$f = new formInput;

					$f->start("$urlPrefix/admin/pictures/move/submit");

					$f->hidden("id", $id);
					$f->hidden("node", $node);
					$f->hidden("to", $to);
					$f->hidden("type", $type);

					// path navbar
					$nav = new picture_navbar($dbi);

					if ($type == 'picture') {
						$qry = "SELECT `name` FROM ${dbTables['pictures']} WHERE id='$id'";
						$dbi->db->query($qry);

						list($name) = $dbi->db->fetch_row();

						echo "<h2>Move " . $name . " to..</h2>\n";

					} else {
						echo "<h2>Move " . $nav->getName($id) . " to..</h2>\n";
					}

					$path = $nav->getPath($to);
					foreach($path as $x => $name) {
						echo "<A HREF=\"$urlPrefix/admin/pictures/move?node=$node&id=$id&to=$x&type=$type\"> $name</a> / ";
					}

					echo "<br>\n\n";

					// display children
					$children = $nav->getChildren($to);
					foreach($children as $child => $name) {
						echo "<li>";

							echo "<a href=\"$urlPrefix/admin/pictures/move?node=$node&id=$id&to=$child&type=$type\">$name</A>";

						echo "</li>\n";
					}
					echo "<br>\n";


					echo "<table width=100% border=0 align=center>\n";

					

					echo "<tr><td colspan=2>"; $f->submit("OK"); echo "</td></tr>\n";

					echo "</table>\n";
					$f->end();
				}
				

				break;

		case "clipboard":

				if ($arg2 == 'reset') {
					unset($_SESSION['picture_clipboard']);
					unset($arg1);
					require(__FILE__);
					break;
				}

				$clip = intval($arg2);

				$picture = new picture($dbi, $clip);
				if (!$picture->id) {
					echo "<b>ERROR:</b> Picture ID not found<br>\n";
					unset($arg1);
					require(__FILE__);
					break;
				}

				$_SESSION['picture_clipboard'] = $clip;

				echo "Set <b>" . stripslashes($picture->name) . "</b> to clipboard.<BR>\n";
				unset($arg1);
				require(__FILE__);
				break;


		case "modify": // change picture properties

			if ($arg2 == 'submit') {
				$id = intval($_POST['id']);

				$picture = new picture($dbi, $id);

				if (!$picture->id) {
					echo "ERROR: Invalid ID passed<br>\n";
					unset($arg1);
					require(__FILE__);
					break;
				}

				foreach($picture->fields as $k) {
					if ($k == 'picture' || $k == 'thumbnail') continue;
					$picture->$k = $_POST[$k];
				}

				$picture->handleUpload('image');

				if (!$picture->save()) {
					echo "<h3>Errors</h3>-\n";
					echo join("<br>-\n", $picture->errors) . "<br><br>\n";
					$arg2 = $id;
					require(__FILE__);
					break;
				}

				echo "Successfully updated <b>" . stripslashes($picture->name) . "</b><br>\n";
				$arg2 = $id;
				require(__FILE__);

			} else {
				$id = intval($arg2);
				$node = intval($_GET['node']);

				$picture = new picture($dbi, $id);

				if (!$picture->id) {
					echo "ERROR: Invalid ID passed<br>\n";
					unset($arg1);
					require(__FILE__);
					break;
				}

				echo "<a href=\"$urlPrefix/admin/pictures?node=$picture->category\">Back to category</a><br>\n";

				foreach($picture->fields as $k) {
					if (empty($_POST[$k])) $_POST[$k] = $picture->$k;
				}


				$img_src = PICTURES_URL_PREFIX . "/" . $picture->thumbnail;
				$pic_src = PICTURES_URL_PREFIX . "/" . $picture->picture;

				$f = new formInput;
				$f->start("$urlPrefix/admin/pictures/modify/submit", "multipart/form-data");

				$f->hidden("MAX_FILE_SIZE", MAX_FILE_SIZE);
				$f->hidden("node", $node);
				$f->hidden("id", $id);
				$f->hidden("category", $_POST['category']);

				echo "<h2>Edit " . stripslashes($picture->name) . "</h2>\n";

				echo "<table width=100% border=0 align=center>\n";
				echo "<tr>";
					echo "<td width=5 valign=top>";
						echo "<a target=\"_NEW\" href=\"$pic_src\">";
							echo "<img style=\"border: 1px solid black;\" src=\"$img_src\">";
						echo "</a>";
					echo "</td>";
					echo "<td valign=top>\n";

						echo "<table width=100% border=0 align=center>\n";
							echo "<tr>\n";
								echo "<td>Date Added</td>";
								echo "<td>"; $f->text("date_added", $_POST['date_added']); echo "</td>\n";
							echo "</tr>\n";

							echo "<tr>\n";
								echo "<td>Category</td>";
								echo "<td>"; 
								echo "<a href=\"$urlPrefix/admin/pictures/move?node=$node&id=$id&to=$node&type=picture\">Change category</a>";
								echo "</td>\n";
							echo "</tr>\n";

							echo "<tr>\n";
								echo "<td>Name</td>";
								echo "<td>"; $f->text("name", $_POST['name']); echo "</td>\n";
							echo "</tr>\n";

							echo "<tr>\n";
								echo "<td valign=top>Description</td>";
								echo "<td>"; $f->textarea("description", 10, 40, $_POST['description']); echo "</td>\n";
							echo "</tr>\n";

							echo "<tr>\n";
								echo "<td>Picture</td>";
								echo "<td>"; $f->file("image"); echo "</td>\n";
							echo "</tr>\n";

							echo "<tr>\n";
								echo "<td>Dimensions</td>";
								echo "<td>"; $f->text("width", $_POST['width'], 4); echo " x "; $f->text("height", $_POST['height'], 4); echo "</td>\n";
							echo "</tr>\n";

							echo "<tr>\n";
								echo "<td>Virtual</td>";
								echo "<td>"; $f->text("vwidth", $_POST['vwidth'], 4); echo " x "; $f->text("vheight", $_POST['vheight'], 4); echo "</td>\n";
							echo "</tr>\n";

						echo "</table>\n";

					echo "</td>\n";
				echo "</tr>\n";


				echo "<tr><td colspan=2>"; $f->submit("Update"); echo "</td></tr>\n";

				echo "</table>\n";

				$f->end();


			}
			break;

		case "remove": // remove picture

			$id = intval($arg2);

			$picture = new picture($dbi, $id);

			if (!$picture->id) {
				echo "<b>ERROR:</b> Invalid ID passed<br>\n";
				unset($arg1);
				require(__FILE__);
				break;
			}

			$picture->delete();

			unset($arg1);
			require(__FILE__);

			break;

		case "upload": // upload image
			if ($arg2 == 'submit') {

				$node = intval($_POST['node']);
				$fcount = intval($_POST['count']);
				for($i=0; $i<$fcount; $i++) {
					$fi = "file" . $i;
					$ni = "name" . $i;
					$name = $_POST[$ni];

					if (empty($_FILES[$fi]['tmp_name'])) {
						echo "<b>$fi</b> is empty<br>\n";
						continue;
					}

					$picture = new picture($dbi);

					if (!$picture->handleUpload($fi)) {
						echo "ERROR: Failed to handle uploaded picture<br>\n";
						continue;
					}

					$picture->name = $name;

					if (!$picture->save()) {
						echo "ERROR: Unable to save picture $fi<br>\n";
						echo join("<br>", $picture->errors) . "<br>\n";
						continue;
					}

					echo "<b>$fi</b>: Uploaded <b>" . htmlspecialchars($_FILES[$fi]['name']) . "</b><br>\n";

				}

				$_GET['node'] = $node;
				unset($arg1);
				require(__FILE__);
				break;

			} else {
				$node = intval($_GET['node']);
				$fcount = intval($_GET['count']);
				if ($fcount <= 0) $fcount = 3;

				echo "<h2>Upload Pictures</h2>\n";

				$form = new formInput;
				$form->start("$urlPrefix/admin/pictures/upload", NULL, "GET");
				$form->hidden("node", $node);
				echo "Upload "; $form->text("count", intval($fcount), 3); echo " images &nbsp; ";
				$form->submit("OK");
				$form->end();



 				$form->start("$urlPrefix/admin/pictures/upload/submit", "multipart/form-data");
				$form->hidden("MAX_FILE_SIZE", MAX_FILE_SIZE);

				$form->hidden("node", intval($_GET['node']));
				$form->hidden("count", $fcount);

				echo "<table width=100% border=0 align=center>\n";

				for($i=0; $i<$fcount; $i++) {

					echo "<tr><td colspan=2><b>Picture $i</b></td></tr>\n";
					echo "<tr>\n";
						echo "<td>Name</td>\n";
						echo "<td>"; $form->text("name$i"); echo "</td>\n";
					echo "</tr>\n";

					echo "<tr>\n";
						echo "<td>File</td>\n";
						echo "<td>"; echo "<input type=\"file\" name=\"file$i\">"; echo "</td>\n";
					echo "</tr>\n";
				}

				echo "<tr><td colspan=2>"; $form->submit("Upload"); echo "</td></tr>\n";

				echo "</table>\n";
				$form->end();

			}

			break;

		case "add": // add category

			if ($arg2 == 'submit') {

				$pic_cat = new picture_cat($dbi);

				$parent = intval($_POST['node']);

				foreach($pic_cat->fields as $k) {
					$pic_cat->$k = $_POST[$k];
				}

				$pic_cat->parent = $parent;
				$pic_cat->picture = "unimplemented";

				if ($pic_cat->save()) {
					echo "Added picture category " . $_POST['name'] . "<br>\n";
					unset($arg1);
					$_GET['node'] = $parent;
					require(__FILE__);
					break;

				} else {
					echo "<h3>Errors</h3>-\n";
					echo join("<br>-\n", $pic_cat->errors);
					unset($arg2);
					require(__FILE__);
					break;

				}

				
				
			} else {

				$node = intval($_GET['node']);

				echo "<h2>Add Category</h2>\n";
				$f = new formInput;
				$f->start("$urlPrefix/admin/pictures/add/submit");

				$f->hidden("node", $node);

				echo "<table width=100% border=0 align=center>\n";

				echo "<tr>\n";
					echo "<td><b>Name</b></td>\n";
					echo "<td>"; $f->text("name", $_POST['name']); echo "</td>\n";
				echo "</tr>\n";

				echo "<tr><td colspan=2>";
				$f->submit("Add Category");
				echo "</td></tr>\n";

				echo "</table>\n";

				$f->end();
			}
			break;

		case "delete": // delete category

			$id = intval($_GET['id']);

			$nav = new picture_navbar($dbi);
			$parent = $nav->getParentID($id);
			$pic_cat = new picture_cat($dbi, $id);

			if (!$pic_cat->id) {
				echo "ERROR: Unable to delete, Invalid ID passed<br>\n";
				unset($arg1);
				require(__FILE__);
				break;
			}


			$children = $nav->getAllChildren($id);

			// first check if there are any pictures in these categories.. if so bomb out! (for now)
			$error = 0;
			foreach($children as $id => $name) {
				$qry = "SELECT COUNT(*) FROM {$dbTables['pictures']} WHERE category='$id'";
				$dbi->db->query($qry);
				list($count) = $dbi->db->fetch_row();
				if ($count) {
					$error = 1;
					break;
				}
			}

			if ($error) {
				echo "<b>ERROR:</b> This folder is not empty<br>\n";
				unset($arg1);
				require(__FILE__);
				break;
			}

			foreach($children as $id => $name) {
				$qry = "DELETE FROM {$dbTables['pictures_cat']} WHERE id='$id'";
				$dbi->db->query($qry);
			}

			$_GET['node'] = $parent;
			unset($arg1);
			require(__FILE__);

			break;

		case "edit": // edit category

			if ($arg2 == 'submit') {

				$id = intval($_POST['id']);
				$node = intval($_POST['node']);

				$pic_cat = new picture_cat($dbi, $id);

				foreach($pic_cat->fields as $k) {
					$pic_cat->$k = $_POST[$k];
				}
				$pic_cat->picture = "unimplemented";

				if ($pic_cat->save()) {
					echo "Updated picture category " . $_POST['name'] . "<br>\n";
					unset($arg1);
					require(__FILE__);
					break;

				} else {
					echo "<h3>Errors</h3>-\n";
					echo join("<br>-\n", $pic_cat->errors);
					unset($arg2);
					require(__FILE__);
					break;

				}

				
			} else {

				$id = intval($_GET['id']);
				$node = intval($_GET['node']);

				$pic_cat = new picture_cat($dbi, $id);

				if (!$pic_cat->id) {
					echo "<h3>ERROR: Invalid ID passed</h3>-\n";
					unset($arg1);
					require(__FILE__);
					break;
				}

				foreach($pic_cat->fields as $k) {
					$_POST[$k] = $pic_cat->$k;
				}

				echo "<h2>Edit Category</h2>\n";
				$f = new formInput;
				$f->start("$urlPrefix/admin/pictures/edit/submit");

				$f->hidden("id", $id);
				$f->hidden("node", $node);
				$f->hidden("parent", $pic_cat->parent);

				echo "<table width=100% border=0 align=center>\n";

				echo "<tr>\n";
					echo "<td><b>Name</b></td>\n";
					echo "<td>"; $f->text("name", $_POST['name']); echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
					echo "<td valign=top><b>Description</b></td>\n";
					echo "<td>"; $f->textarea("description", 10, 40, $_POST['description']); echo "</td>\n";
				echo "</tr>\n";

				echo "<tr><td colspan=2>";
				$f->submit("Update Category");
				echo "</td></tr>\n";

				echo "</table>\n";

				$f->end();
			}
			break;


		default:
			$node = intval($_GET['node']);

			$nav = new picture_navbar($dbi);

			?>
			<H2>Pictures</H2>
			<li><A HREF="<?= "$urlPrefix/admin/pictures/add?node=$node" ?>">Add Category</A></li>
			<li><A HREF="<?= "$urlPrefix/admin/pictures/upload?node=$node" ?>">Upload Picture(s)</A></li>
			<br>
			<?

			// clipboard
			if ($loggedIn) {

				echo "<b>Clipboard</b>: ";
				$clip = intval($_SESSION['picture_clipboard']);
				if ($clip) {
					$qry = "SELECT `name` FROM {$dbTables['pictures']} WHERE id='$clip'";
					if ($dbi->db->query($qry)) {
						list($name) = $dbi->db->fetch_row();
						echo $name;

					} else {
						echo "<i>Error</i>";
						echo $dbi->db->error();
					}
				} else {
					echo "Empty";
				}

				if ($clip) echo " &nbsp; <a href=\"$urlPrefix/admin/pictures/clipboard/reset?node=$node\">Reset</a>.";

				echo "<br><br>\n";
			}

			// path navbar
			$path = $nav->getPath($node);
			foreach($path as $id => $name) {
				echo "<A HREF=\"$urlPrefix/admin/pictures?node=$id\"> $name</a> / ";
			}

			if ($id > 0) {
				$move_url = "$urlPrefix/admin/pictures/move?node=$node&id=$id&to=$node";
				$edit_url = "$urlPrefix/admin/pictures/edit?node=$node&id=$id";
				$delete_url = "$urlPrefix/admin/pictures/delete/?node=$node&id=$id";

				echo " &nbsp; [";
				echo "<a title=\"edit\" href=\"$edit_url\">edit</a> ";
				echo "<a title=\"delete\" href=\"$delete_url\">delete</a> ";
				echo "<a title=\"move\" href=\"$move_url\">move</a> ";
				echo "]";
			}
			echo "<br>\n\n";

			// display children
			$children = $nav->getChildren($node);
			foreach($children as $child => $name) {

				$name_url = "$urlPrefix/admin/pictures/?node=$child";
				$move_url = "$urlPrefix/admin/pictures/move?node=$node&id=$child&to=$node";
				$edit_url = "$urlPrefix/admin/pictures/edit?node=$node&id=$child";
				$delete_url = "$urlPrefix/admin/pictures/delete/?node=$node&id=$child";

				echo "<li>";

				echo "<a href=\"$name_url\">$name</A>";

				echo " &nbsp; [";

				echo "<a title=\"edit\" href=\"$edit_url\">edit</a> ";
				echo " <a title=\"delete\" href=\"$delete_url\">delete</a> ";
				echo " <a title=\"move\" href=\"$move_url\">move</a> ";

				echo "]</li>\n";
			}
			echo "<br>\n";

			// display what's there
			$qry = "SELECT * FROM {$dbTables['pictures']} WHERE category='$node' ORDER BY `name` ASC";

			if (!$dbi->db->query($qry)) {
				echo "ERROR: " . $dbi->db->error() . "<br>\n";
				break;
			}

			if (!$dbi->db->num_rows()) {
				echo "No pictures to display<br>\n";
			}

			echo "<table width=100% border=0 align=center>\n";
			while ($obj = $dbi->db->fetch_object() ) {

				$img_src = PICTURES_URL_PREFIX . "/" . $obj->thumbnail;
				$edit_url = "$urlPrefix/admin/pictures/modify/$obj->id?node=$node";
				$delete_url = "$urlPrefix/admin/pictures/remove/$obj->id?node=$node";
				$move_url = "$urlPrefix/admin/pictures/move?node=$node&id=$obj->id&to=$node&type=picture";
				$clip_url = "$urlPrefix/admin/pictures/clipboard/$obj->id?node=$node";
				$transform_url = "$urlPrefix/admin/pictures/transform/$obj->id?node=$node";

				echo "<tr>";
						echo "<td><b>" . stripslashes($obj->name) . "</b></td>";

						echo "<td align=right>";
							echo "<a href=\"$edit_url\">edit</a> &nbsp; ";
							echo "<a href=\"$delete_url\">delete</a> &nbsp; ";
							if ($loggedIn) echo "<a href=\"$clip_url\">clipboard</a> &nbsp; ";
							echo "<a href=\"$move_url\">move</a> &nbsp; ";
							echo "<a href=\"$transform_url\">transform</a> &nbsp; ";
						echo "</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";

				echo "<tr><td colspan=2>\n";

					echo "<table width=100% border=0>\n";
					echo "<tr>\n";

					echo "<td width=1>";
						echo "<a href=\"$edit_url\"><img src=\"$img_src\" border=0 style=\"border: 1px solid black;\"></a></td>\n";
					echo "</td>";

					echo "<td valign=top>\n";
						echo "Seen $obj->view_count times<hr size=1>\n";

						echo empty($obj->description) ? "<i>No description</i>" : htmlspecialchars(stripslashes($obj->description));
					echo "</td>\n";

					echo "</tr>\n";
					echo "</table>";
				echo "</td></tr>\n";

				echo "</tr>\n";

				echo "<tr><td colspan=2>&nbsp;</td></tr>\n";
			}

			echo "</table>\n";

			break;
	}
?>
