<?

class mod_htmlProtect {
		var $functions;

		/* constructor */
		function mod_htmlProtect() {
			$this->functions = array('HTMLProtect');
		}

		function getFunctions() {
			return $this->functions;
		}

		function HTMLProtect($str) {
         $l = strlen($str);
         for($i=0; $i<$l; $i++) {
            $o = ord($str[$i]);
            $rv .= "&#x" . dechex($o) . ";";
         }
         return $rv;
      }

	
}
?>
