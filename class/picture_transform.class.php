<?
	class picture_transform {

		var $file; // complete path

		var $image;
		var $width, $height, $type, $attr;

		var $color_white, $color_black;

		// constructor 
		function picture_transform($f) {

			$this->file = NULL;

			if (!file_exists($f)) return FALSE;
			$this->file = $f;

			list($width, $height, $type, $attr) = getimagesize($this->file);

			$this->width = $width;
			$this->height = $height;
			$this->type = $type;
			$this->attr = $attr;

			$this->loadImage();
			$this->loadBasicColors();

			return TRUE;
		}

		function loadBasicColors() {
			$this->color_black = imageColorAllocate($this->image, 0, 0, 0);
			$this->color_white = imageColorAllocate($this->image, 255, 255, 255);
		}

		function loadImage() {
			switch ($this->type) {
				case IMAGETYPE_GIF:
					$this->image = imageCreateFromGIF($this->file);
       			break;

				case IMAGETYPE_JPEG:
					$this->image = imageCreateFromJPEG($this->file);
					break;

				case IMAGETYPE_PNG:
					$this->image = imageCreateFromPNG($this->file);
					break;

				case IMAGETYPE_WBMP:
					$this->image = imageCreateFromWBMP($this->file);
					break;

				default:
					$this->image = NULL;
					break;
			}
		}


		function saveImage() {
			switch ($this->type) {
				case IMAGETYPE_GIF:
					imageGIF($this->image, $this->file);
       			break;

				case IMAGETYPE_JPEG:
					imageJPEG($this->image, $this->file);
					break;

				case IMAGETYPE_PNG:
					imagePNG($this->image, $this->file);
					break;

				case IMAGETYPE_WBMP:
					imageWBMP($this->image, $this->file);
					break;
			}
		}


		function aspectResizeDimensions($max_width) {
			$imagesize_x = $this->width;
			$imagesize_y = $this->height;
			$thumb_width = $max_width;
			$thumb_height = (int) (($max_width * $imagesize_y) / $imagesize_x);
			$ar = array($thumb_width, $thumb_height);
			return $ar;
		}

		function thumbnail($max_width = 200) {
			list($thumb_width, $thumb_height) = $this->aspectResizeDimensions($max_width);
			@$im = imageCreate($thumb_width, $thumb_height);
			if (!$im) return FALSE;
			imageCopyResampled($im, $this->image, 0, 0, 0, 0, $thumb_width, $thumb_height, $this->width, $this->height);
			return $im;
		}

		function string($font, $x, $y, $txt) {
			imageString($this->image, $font, $x, $y, $txt, $this->color_black);
		}

		function text($size, $angle, $x, $y, $color_ar, $fontfile, $text) {
			// $color_ar = array( red, green, blue )
			$color = imageColorAllocate($this->image, $color_ar[0], $color_ar[1], $color_ar[2]);
			imageTTFText($this->image, $size, $angle, $x, $y, $color, $fontfile, $text);
		}

		function rotate($d) {
			$this->image = imageRotate($this->image, $d, $this->color_black);
		}

		function save() {
			$this->saveImage();
		}
	}
?>
