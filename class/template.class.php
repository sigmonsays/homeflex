<?
	/*
			simple template class
			2004-01-27
			Sig Lange <exonic@signuts.net>
			http://www.signuts.net/

			basic tag structure

			<%templ [func](arg1, arg2, ...) %>


	*/

	class template {
		var $tags, $tagCount;

		var $template_data;
		var $template_resource;

		var $start_tag, $end_tag;

		var $module_functions;

		/* constructor */
		function template() {

			$this->tags = NULL;
			$this->tagCount = 0;

			$this->template_resource = NULL;
			$this->template_data = NULL;

			$this->start_tag = "<%templ ";
			$this->end_tag = "%>";

			$this->module_functions = array();

		}

		function parseArguments($str) {

			$args = array();

			$len = strlen($str);
			$i = 0;
			$s = 0;
			$in = 0;
			do {
				if ($str[$i] == "'") {
					$s = $i++;
					for( ; $str[$i] != "'" && $i < $len; $i++);
					if ($str[$i] == "'") {
						$s++;
						$args[] = substr($str, $s, $i - $s);
						$i++;
						for( ; $str[$i] != "'" && $str[$i] != "," && $i < $len - 1; $i++);
						$s = $i;
						continue;
					}

				} else if ($str[$i] == ",") {
					$args[] = trim(substr($str, $s, $i - $s));
					$s = $i;
				}
			} while (++$i < $len);

			if ($s != $len) {
				$args[] = trim(substr($str, $s + 1));
			}
			return $args;
		}


		function addTag($tagName, $type) {
			if ($this->tagExists($tagName)) return FALSE;

			$tag = new stdClass;

			$tag->type = $type;
			$tag->name = $tagName;

			$args = array_slice(func_get_args(), 2);
			$tag->args = $args;


			$this->tags[$tagName] = $tag;
			$this->tagCount++;

			return TRUE;

		}

		function tagExists($tagName) {
			if (isset($this->tags[$tagName])) return TRUE;
			return FALSE;
		}

		function deleteTag($tagName) {
			if ($this->tagExists($tagName)) return FALSE;
			unset($this->tags[tagName]);
			$this->tagCount--;
			return TRUE;
		}


		/* convenience functions for adding tags */

		function addTagInclude($tagName, $include) {
			if (!file_exists($include)) return FALSE;
			$r = $this->addTag($tagName, "include", $include);
			return $r;
		}

		function addTagFunction($tagName, $function) {
			$r = $this->addTag($tagName, "function", $function);
			return $r;
		}

		function addTagReturnFunction($tagName, $function) {
			$r = $this->addTag($tagName, "rfunction", $function);
			return $r;
		}

		function addTagDefine($tagName, $define) {
			if (!defined($define)) return FALSE;
			$r = $this->addTag($tagName, "define", $define);
			return $r;
		}

		function addTagVariable($tagName, $variable) {
			$r = $this->addTag($tagName, "var", $variable);
			return $r;
		}

		function addTagPostVariable($tagName, $variable) {
			$r = $this->addTag($tagName, "postvar", $variable);
			return $r;
		}

		function addTagGetVariable($tagName, $variable) {
			$r = $this->addTag($tagName, "getvar", $variable);
			return $r;
		}


		/* load the template functions */
		function loadTemplate($resource, $template_data) {
			$this->template_resource = $resource;
			$this->template_data = $template_data;
			return TRUE;
		}

		function loadTemplateFromFile($file) {
			if (!file_exists($file)) return FALSE;

			$data = join("", file($file));
			$this->loadTemplate("file", $data);
			return TRUE;
		}

		function loadTemplateFromString($string) {
			$r = $this->loadTemplate("string", $string);
			return $r;
		}


		/* functions to pull tag data */

		function doTagDefine($define) {
			$content = constant($data);
			return $content;
		}

		function doTagInclude($include) {
			if (!file_exists($include)) return NULL;

			extract($GLOBALS, EXTR_REFS);

			ob_start();
			require($include);
			$content = ob_get_contents();
			ob_end_clean();

			return $content;
		}

		function doTagFunction($function) {
			if (!function_exists($function)) return NULL;

			extract($GLOBALS, EXTR_REFS);

			ob_start();
			$function();
			$content = ob_get_contents();
			ob_end_clean();

			return $content;
		}

		function doTagReturnFunction($function) {
			if (!function_exists($function)) return NULL;
			$content = $function();
			return $content;
		}

		function doTagVariable($variable) {
			return $GLOBALS[$variable];
		}

		function doTagPostVariable($variable) {
			return $_POST[$variable];
		}

		function doTagGetVariable($variable) {
			return $_GET[$variable];
		}


		/* module code */
		function loadModule($file) {
			if (!file_exists($file)) {
				return FALSE;
			}

			$moduleName = basename($file, ".mod.php");
			require_once($file);

			$module = new $moduleName();

			foreach($module->getFunctions() as $moduleFunc) {
				$this->addModuleFunction($module, $moduleFunc);
			}

			return TRUE;
		}

		function moduleFunctionExists($function) {
			if (isset($this->module_functions[$function])) return TRUE;
			return FALSE;
		}

		function & getModuleFunction($function) {
			if (!$this->moduleFunctionExists($function)) return FALSE;
			return $this->module_functions[$function];
		}

		function doModuleFunction($function, $arguments) {

			$args_array = $this->parseArguments($arguments);

			$module = $this->getModuleFunction($function);
			$class_name = get_class($module);
			$rv = call_user_func_array( array($class_name, $function), $args_array);
			return $rv;
		}

		function addModuleFunction(&$moduleInstance, $moduleFunc) {

			$this->module_functions[$moduleFunc] = $moduleInstance;

			return TRUE;
		}


		/* parser code */
		function parserHasWork($data) {
			$i = strpos($data, $this->start_tag);
			if ($i === FALSE) return FALSE;
			return TRUE;
		}

		function codeParser($code, $len) {
			$this->doCodeParser($code, $len);
		}

		function doCodeParser($code, $len) {
			$data = $this->doTemplateCode($code, $len);

			if ($this->parserHasWork($data)) {
				echo $this->doParseTemplate($data);
			} else {
				echo $data;
			}
		}

		function doParseTemplate($data) {

			$start_tag_len = strlen($this->start_tag);
			$end_tag_len = strlen($this->end_tag);

         $l = strlen($data);

         $o = $p = strpos($data, $this->start_tag);
         $lp = 0;
         if ($p === FALSE) {
				echo $data;
			} else {
            do {

					echo substr($data, $lp, $p - $lp);

               $p2 = strpos($data, "%>", $p + 1);

               if ($p2) {
						$length = $p2 - $p - $start_tag_len;
						$templ_code = substr($data, $p + $start_tag_len, $length);

						$this->codeParser($templ_code, $length);

                  $p = $p2;

               } else {
                  /* no close tag */
                  $o = $p + $start_tag_len;
                  break;
               }

               $lp = $p2 + $end_tag_len;
               $o = $p2 + $end_tag_len;

					flush();

            } while (($o < $l) && ($p = strpos($data, $this->start_tag, $o + 1)) );

				echo substr($data, $o);
         }
		}

		function parseTemplate() {
			$this->doParseTemplate($this->template_data);
			return TRUE;
		}


		/* main interface to all template code */
		function doTemplateCode($code_block, $code_len) {

			$lines = explode(";", $code_block);
			$c = count($lines);

			$out = "";
			for($i=0; $i<$c; $i++) {

				$code = trim($lines[$i]);

				$start_func = strpos($code, '(');
				if ($start_func === FALSE) {
					continue;
				}

				$end_func = strpos($code, ')');

				if ($end_func === FALSE) {	// no ')' found
					continue;
				}

				$templ_func = substr($code, 0, $start_func);
				$templ_args = substr($code, $start_func + 1, $end_func - $start_func - 1);

				if ($templ_func == 'tag') {
					$out .= $this->doTemplateTag($templ_args);

				} else if ($templ_func == 'echo') {
					$out .= $this->doTemplateEcho($templ_args);

				} else if ($templ_func == 'date') {
					$out .= $this->doTemplateDate($templ_args);

				} else if ($templ_func == 'rand') {
					$out .= $this->doTemplateRand($templ_args);

				} else if ($templ_func == 'sqlList') {
					$out .= $this->doTemplateSqlList($templ_args);

				} else {

					if ($this->moduleFunctionExists($templ_func)) {

						$out .= $this->doModuleFunction($templ_func, $templ_args);

					} else {
						echo "<I><B>WARNING</B>: Unknown function <B>$templ_func</B></I><BR>\n";
					}
				}

			}

			return $out;

		}

		/* tag handling functions */
		function doTemplateTag($tag) {
			$out = NULL;
			if (!$this->tagExists($tag)) return NULL;

			$tag = & $this->tags[$tag];

			if ($tag->type == 'include') {
				$out = $this->doTagInclude($tag->args[0]);

			} else if ($tag->type == 'function') {
				$out = $this->doTagFunction($tag->args[0]);

			} else if ($tag->type == 'rfunction') {
				$out = $this->doTagReturnFunction($tag->args[0]);

			} else if ($tag->type == 'define') {
				$out = $this->doTagDefine($tag->args[0]);

			} else if ($tag->type == 'var') {
				$out = $this->doTagVariable($tag->args[0]);

			} else if ($tag->type == 'postvar') {
				$out = $this->doTagPostVariable($tag->args[0]);

			} else if ($tag->type == 'getvar') {
				$out = $this->doTagGetVariable($tag->args[0]);

			}

			return $out;
		}

		function doPHPFunction($function) {
			if (!function_exists($function)) return FALSE;
			$args = array_slice(func_get_args(), 1);
			$res = call_user_func_array($function, $args);
			return $res;
		}

		function doTemplateEcho($str) {
			return $str;
		}

		function doTemplateDate($format) {
			if (empty($format)) $format = "r";
			return $this->doPHPFunction("date", $format);
		}

		function doTemplateRand($args) {
			list($min, $max) = $this->parseArguments($args);
			return $this->doPHPFunction("rand", $min, $max);
		}

		function doTemplateSqlList($args) {
			global $dbTables, $dbi;
			list($qry, $entry_str, $entry_str_selected, $selected_vars, $globals) = $this->parseArguments($args);

			foreach(explode(",",$globals) as $var) {
				global $$var;
			}

			$eval_qry = eval('return "' . $qry . '";');

			$dbi->db->query($eval_qry);

			$entry_str = addcslashes($entry_str, '"');
			$entry_str_selected = addcslashes($entry_str_selected, '"');

			list($p1, $p2) = explode(",", $selected_vars);

			$out = "";
			while($entry = $dbi->db->fetch_object()) {

				$eval_p1 = eval('return "' . $p1 . '";');
				$eval_p2 = eval('return "' . $p2 . '";');


				if ($eval_p1 == $eval_p2) {
					$eval_entry = eval('return "' . $entry_str_selected . '";');
				} else {
					$eval_entry = eval('return "' . $entry_str . '";');
				}
				$out .= $eval_entry;
			}

			return $out;
		}

		function doTemplateHTMLProtect($str) {
			$l = strlen($str);
			for($i=0; $i<$l; $i++) {
				$o = ord($str[$i]);
				$rv .= "&#x" . dechex($o) . ";";
			}
			return $rv;
		}
	}
?>
