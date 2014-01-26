<?
	function word_definition($word) {
        
		$data = strtolower(join("", file("http://dictionary.reference.com/search?q=$word")));
        
		list(, $stuff) = split('<!-- content -->', $data);
        
		list(, , $foo) = split('<table', $stuff);
        
		$foo = strip_tags($foo, "<BR>");
                
		return substr($foo, 1);
	}

	function write_data($name, $stuff, $ext = "txt", $mode = "w+") {
      $fp = fopen("./randomness/$name.$ext", $mode);
      if ($fp) {
         fputs($fp, $stuff);
         fclose($fp);
      }
   }

?>
