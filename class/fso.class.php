<?
	class fso {

		function loadDirectory($dir) {
	      @$d = opendir($dir);
      if ($d) {
         readdir($d);
         readdir($d);
         $files = array();
         while ($file = readdir($d)) {
					$type = filetype("$dir/$file");

					$files[$type][] = $file;
         }
         closedir($d);

         return $files;
      } else
         return FALSE;
		}
	}
?>
