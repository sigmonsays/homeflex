<?
	class sqlForm extends formInput {
		var $dbi, $table, $database;
		var $fields;
		var $fieldTypes;
		var $fieldArgs;
		var $fieldFuncs;
		var $fieldLinks;
		var $postVars;
		var $urlPrefix;
		var $fieldCount;
		var $mode;

		/* used for selectSource to track the table,id and name fields for select field types */
		var $fieldTables;

		var $id, $value; /* temp holding spot for current unique id and value */

		var $uniqueField, $actionVariable;

		var $sqlOrderBy, $sqlStart, $sqlOffset, $sqlWhere;

		var $urlVars;
		var $version;

		var $rowColors;
		var $cellpadding;

		var $title;

		var $additionalLinks;

		var $dbi; /* database interface */

		/* constructor */
		function sqlForm(&$dbi, $table, $database, $db_type = "mysql") {
			$this->horizontal_row_spacing = 0;
			$this->vertical_row_spacing = 0;

			$this->dbi = $dbi;
			$this->table = $table;
			$this->database = $database;
			$this->actionVariable = "action";
			$this->urlVars = "";

			$this->rowColors = "#FFFFFF,#e8e8e8";
			$this->cellpadding = 4;


			list($major, $minor, $revision) = split("\.", phpversion());
			$this->version = intval($major . $minor . $revision);

			if (!$dbi) return -1;

			if (!$dbi->db->conn) {
				$dbi->db->connect();
			}

			if (!empty($database) && !$dbi->db->select_db($database)) return -1;

			$this->postVars = array();

			$this->title = ucwords($this->table);

			/* list fields */
			$fieldList = $this->dbi->db->list_fields($table);

			$columns = $this->dbi->db->num_fields($fieldList);

			for ($i = 0; $i < $columns; $i++) {

				$x = $i;

				$this->fields[$i] = $this->dbi->db->field_name($fieldList, $x);
				$this->fieldTypes[$i] = "text";
				$this->fieldArgs[$i] = array();
				$this->fieldFuncs[$i] = "";
				$this->fieldFuncArgs[$i] = array();
				$this->fieldLinks[$i] = "";
			}
			$this->fieldCount = $columns;
			$this->uniqueField = "id";
			$this->sqlStart = 0;
			$this->sqlOffset = 30;
			$this->mode = "list,add,edit,delete";
			$this->fieldTables = array();
		}


		/* this function performs a translation, mostly it's a dirty hack for supporting date() from formInput.class ... 
		   however I could possibly see it being useful in the future for more translations .... */

		function translate_value($fieldName, $fieldValue) {
			$fieldID = $this->fieldIDFromName($fieldName);
			$fieldType = $this->fieldTypes[$fieldID];
			$func_args = $this->fieldArgs[$fieldID];

			switch ($fieldType) {
				case "date":
					if (is_array($fieldValue)) {

						/* we need to put missing values in this array -- hour, minute, sec, etc */
						if (empty($fieldValue['hour'])) $fieldValue['hour'] = 1;
						if (empty($fieldValue['minute'])) $fieldValue['minute'] = 1;
						if (empty($fieldValue['second'])) $fieldValue['second'] = 1;
						if (empty($fieldValue['month'])) $fieldValue['month'] = 1;
						if (empty($fieldValue['day'])) $fieldValue['day'] = 1;
						if (empty($fieldValue['year'])) $fieldValue['year'] = 1;

						$newFieldValue = mktime($fieldValue['hour'], $fieldValue['minute'], $fieldValue['second'], $fieldValue['month'], 
									$fieldValue['day'], $fieldValue['year']);

						/* translate the field value to the appropriate timestamp format */
						if ($func_args[3] == "sql-timestamp") {
							$newFieldValue = $this->epoch2sql($newFieldValue);

						} else if ($func_args[3] == "date") {
							$newFieldValue = $fieldValue['year'] . "-" . $fieldValue['month'] . "-" . $fieldValue['day'];
						}
						return $newFieldValue;
					} else {
						/* we don't know what to do here
							the variable they gave us wasn't an associative array like we expected, so lets just return the value
							untouched. We dont' even need this else besides for this comment =) 
						*/
						echo "WARNING: Unexpected variable type \$fieldValue in translate_value(..)!<BR>";
						return $fieldValue;
					}
					break;
				

				default: // no translation needed, return the value untouched
					return $fieldValue;
					break;
			}
		}

		function setFieldFunction($fieldName, $function, $type = "user-defined") {
			$fieldID = $this->fieldIDFromName($fieldName);
			$this->fieldFuncs[$fieldID] = ($type == "user-defined") ? $function : "built-in:$function";
			$func_args = func_get_args();
			$this->fieldFuncArgs[$function] = $func_args;
		}

		function formInput($fieldName, $fieldValue = "") {
			$fieldType = $this->getFieldType($fieldName);
			$fieldID = $this->fieldIDFromName($fieldName);

			$func_args = $this->fieldArgs[$fieldID];

			switch ($fieldType) {

				case "select":
					$this->select($fieldName, $func_args[2], $fieldValue);
					break;

				case "password":
					$this->password($fieldName);
					break;

				case "radio":
					$this->radio($fieldName, $fieldValue, $func_args[2]);
					break;

				case "submit":
					$this->submit($fieldName);
					break;

				case "boolean":
					$this->boolean($fieldName, $this->value);
					break;

				case "checkbox":
					$this->checkbox($fieldName, $func_args[2], intval($fieldValue));
					break;

				case "file":
					$this->file($fieldName);
					break;

				default:
					echo "<I><SMALL>Assuming input type text</SMALL></I><BR>";
				case "text":
					$this->text($fieldName, $fieldValue, $func_args[2]);
					break;

				case "textarea":
					$this->textarea($fieldName, $func_args[2], $func_args[3], $fieldValue);
					break;

				case "date":
					/* perform a simple translation on $fieldValue, it's by default an associative array, we need to 
					   make it a unix timestamp */
					if (is_array($fieldValue)) {
						if ($this->version < 410) global $HTTP_POST_VARS;
						$post_vars = ($this->version < 410) ? $HTTP_POST_VARS : $_POST;

						extract($post_vars[$fieldName], "EXTR_PREFIX_ALL", "date");
						$fieldValue = mktime($date_hour, $date_minute, $date_second, $date_month, $date_day, $date_year);
					}
					$this->date($fieldName, $func_args[2], $fieldValue, $func_args[3]);
					break;
			}
		}

		function getFieldType($fieldName) {
			return $this->fieldTypes[$this->fieldIDFromName($fieldName)];
		}

		function setFieldType($field, $type) {
			$fieldID = $this->fieldIDFromName($field);

			$this->fieldArgs[$fieldID] = func_get_args();
			$this->fieldTypes[$fieldID] = $type;

			$this->id = $fieldID;
			$this->value = $field;

			if ($type == "date") {
				if ($this->fieldArgs[$fieldID][3] == 'sql-timestamp') {
					$this->setFieldFunction($field, "timestamp", "F d Y");
				}

			} else if ($type == "select") {

				list($field_table, $field_id, $field_name) = $this->fieldTables[$fieldID];

				$this->setFieldFunction($field, "table", "built-in");
			}
		}

		function fieldIDFromName($name) {
			for($i=0; $i<$this->fieldCount; $i++)
				if ($this->fields[$i] == $name) return $i;
			return -1;
		}


		function addForm($displayFieldsList) {
			$modes = split(",", $this->mode);
			if (!in_array("add", $modes)) return FALSE;

			$displayFields = split(",", $displayFieldsList);
			echo "<H2>Add $this->title</H2>";

			$action = $this->urlPrefix;
			$action .= (!empty($this->urlVars)) ? "?" . $this->urlVars . "&$this->actionVariable=addSubmit" : "/?$this->actionVariable=addSubmit";

			echo "<FORM METHOD=\"post\" ACTION=\"$action\" ENCTYPE=\"multipart/form-data\">\n";
			echo "<INPUT TYPE=\"hidden\" NAME=\"start\" VALUE=\"$this->sqlStart\">\n";
			foreach($this->postVars as $key => $val) {
				echo "<INPUT TYPE=\"hidden\" NAME=\"$key\" VALUE=\"" . htmlspecialchars($val) . "\">\n";
			}
			echo '<TABLE WIDTH="100%" BORDER=0 ALIGN="center">';
			for($i=0; $i<$this->fieldCount; $i++) {
				$fieldName = $this->fields[$i];
				if (in_array($fieldName, $displayFields)) {
					echo "<TR>";
						echo "<TD VALIGN=\"top\">" . ucwords($fieldName) ."</TD>";
						echo "<TD VALIGN=\"top\">";
							$this->formInput($fieldName);
						echo "</TD>";
					echo "</TR>\n";
				}
			}
			?>
			<TR><TD COLSPAN=<?= $this->fieldCount ?> ALIGN="center"><INPUT TYPE="submit" VALUE="Add"></TD></TR>
			</TABLE>
			</FORM>
			<?
		}

		function postVariable($var, $val) {
			$this->postVars[$var] = $val;
		}


		function addFormSubmit($displayFieldsList) {
			$modes = split(",", $this->mode);
			if (!in_array("add", $modes)) return FALSE;

			$displayFields = split(",", $displayFieldsList);
			$c = 0;

			$fieldCount = count($displayFields) - count($_FILES);

			if ($this->version < 410) global $HTTP_POST_VARS;
			$post_vars = ($this->version < 410) ? $HTTP_POST_VARS : $_POST;

			$qryb = ""; $qrya = "";
			foreach($post_vars as $var => $val) {
				if (!in_array($var, $displayFields)) continue;

            $fieldID = $this->fieldIDFromName($var);

				if ($this->fieldTypes[$fieldID] == "password") {
					if ($this->fieldArgs[$fieldID][2] == "mysql") {
						$sql = $this->dbi->db->query("SELECT PASSWORD('" . $this->dbi->db->escape_string($val) . "')");
						if ($sql && $this->dbi->db->num_rows($sql)) {
							list($val) = $this->dbi->db->fetch_row($sql);
						}
					}

					if ($this->fieldArgs[$fieldID][3] == TRUE && !empty($val)) {
						$val = $this->translate_value($var, $val);
						$qry .= "`$var`='" . $this->dbi->db->escape_string(stripslashes($val)) . "'";
						if ($i < $c - 1) $qry .= ", ";
					}
				} else {
					$val = $this->translate_value($var, $val);
					$qry .= "`$var`='" . $this->dbi->db->escape_string(stripslashes($val)) . "'";
					if ($i < $c - 1) $qry .= ", ";
				}

				$c++;
				$qrya .= "`$var`";
				if ($c < $fieldCount) $qrya .= ", ";

				/* make any needed translations on the field */
				$val = $this->translate_value($var, $val);

				$qryb .= "'" . $this->dbi->db->escape_string(stripslashes($val)) . "'";
				if ($c < $fieldCount) $qryb .= ", ";

			}

			$qry = "INSERT INTO `$this->table` (" . $qrya . ') VALUES(' . $qryb . ')';

			$sql = $this->dbi->db->query($qry);

			$ID = $this->dbi->db->insert_id();

			foreach($_FILES as $fieldName => $file) {

					$fieldID = $this->fieldIDFromName($fieldName);
					if ($this->fieldTypes[$fieldID] == 'file') {
						$dest =  $this->fieldArgs[$fieldID][2] . "/" . $file['name'];

						if (is_uploaded_file($file['tmp_name'])) {
							if (@move_uploaded_file($file['tmp_name'], $dest)) {
								$qry = "UPDATE `$this->table` SET `$fieldName` = '" . $this->dbi->db->escape_string($file['name']) . "'"
											. " WHERE `$this->uniqueField` = '$ID'";
								$sql = $this->dbi->db->query($qry);

							} else {
								echo "Warning: $fieldName wasn't a valid uploaded file<BR>\n";
							}
						}
					}
			}

		}

		function editForm($displayFieldsList, $id) {
			$modes = split(",", $this->mode);
			if (!in_array("edit", $modes)) return FALSE;

			$displayFields = split(",", $displayFieldsList);
			$this->id = $id;

			$action = $this->urlPrefix;
			$action .= (!empty($this->urlVars)) ? "?" . $this->urlVars . "&" : "/?";
			$action .= "$this->actionVariable=editSubmit&$this->uniqueField=$id";

			?>
			<H2>Edit <?= $this->title ?></H2>
			<FORM METHOD="post" ACTION="<?= $action ?>" ENCTYPE="multipart/form-data">
			<INPUT TYPE="hidden" NAME="start" VALUE="<?= $this->sqlStart ?>">
			<TABLE WIDTH="100%" BORDER=0 ALIGN="center">
			<?
			$sql = $this->dbi->db->query("SELECT * FROM `$this->table` WHERE $this->uniqueField = '$id'");
			if (!$sql) return -1;
			if (!$this->dbi->db->num_rows($sql)) {
				echo "<B>ERROR</B>: Record not found<BR><BR>\n";
				return -1;
			}

			$obj = $this->dbi->db->fetch_object($sql);

			for($i=0; $i<$this->fieldCount; $i++) {
				$fieldName = $this->fields[$i];
				$this->value = $obj->$fieldName;
				if (in_array($fieldName, $displayFields)) {
					echo "<TR>";
						echo "<TD VALIGN=\"top\">" . ucwords($fieldName) ."</TD>";
						echo "<TD VALIGN=\"top\">";
							$this->formInput($fieldName, $obj->$fieldName);

							if ($this->fieldTypes[$i] == 'file' && $this->fieldArgs[$i][4] == 'image') {

								$localFile = $this->fieldArgs[$i][2] . "/" . basename($obj->$fieldName);
								if (file_exists($localFile) && !empty($obj->$fieldName)) {
									echo "<BR><IMG SRC=\"" . $this->fieldArgs[$i][3] . "/" . basename($obj->$fieldName) . "\">\n";
								} else {
									echo "<BR><I>No Image</I>\n";
								}
							}
						echo "</TD>";
					echo "</TR>\n";
				}
			}
			?>
			<TR><TD COLSPAN=<?= $this->fieldCount ?> ALIGN="center"><INPUT TYPE="submit" VALUE="Update"></TD></TR>
			</TABLE>
			</FORM>
			<?
		}

		function editFormSubmit($displayFieldsList, $id) {
			$modes = split(",", $this->mode);
			if (!in_array("edit", $modes)) return FALSE;

			$displayFields = split(",", $displayFieldsList);
			$qry = "UPDATE `$this->table` SET ";
			$c = count($displayFields) - count($_FILES);

			if ($this->version < 410) global $HTTP_POST_VARS;
			$post_vars = ($this->version <= 406) ? $HTTP_POST_VARS : $_POST;

			for($i=0; $i<$c; $i++) {
				$fieldName = $displayFields[$i];
				$fieldValue = $post_vars[$fieldName];

				if (isset($_FILES[$fieldName]['name'])) continue;

				$fieldID = $this->fieldIDFromName($fieldName);


				if ($this->fieldTypes[$fieldID] == "password") {
					if ($this->fieldArgs[$fieldID][2] == "mysql") {
						$sql = $this->dbi->db->query("SELECT PASSWORD('" . $this->dbi->db->escape_string($fieldValue) . "')");
						if ($sql && $this->dbi->db->num_rows($sql)) {
							list($fieldValue) = $this->dbi->db->fetch_row($sql);
						}
					}

					if ($this->fieldArgs[$fieldID][3] == TRUE && !empty($fieldValue)) {
						$fieldValue = $this->translate_value($fieldName, $fieldValue);
						$qry .= "`$fieldName`='" . $this->dbi->db->escape_string(stripslashes($fieldValue)) . "'";
						if ($i < $c - 1) $qry .= ", ";
					}
				} else {
					$fieldValue = $this->translate_value($fieldName, $fieldValue);
					$qry .= "`$fieldName`='" . $this->dbi->db->escape_string(stripslashes($fieldValue)) . "'";
					if ($i < $c - 1) $qry .= ", ";
				}
			}
			$qry .= " WHERE $this->uniqueField = '$id'";
			$sql = $this->dbi->db->query($qry);
			if (!$sql) echo $this->dbi->db->error();

			/* deal with uploaded files */
			foreach($_FILES as $fieldName => $file) {

					if (empty($file['name'])) continue;

					$fieldID = $this->fieldIDFromName($fieldName);
					if ($this->fieldTypes[$fieldID] == 'file') {
						$dest =  $this->fieldArgs[$fieldID][2] . "/" . $file['name'];

						if (is_uploaded_file($file['tmp_name'])) {
							if (@move_uploaded_file($file['tmp_name'], $dest)) {
								$qry = "UPDATE `$this->table` SET `$fieldName` = '" . $this->dbi->db->escape_string($file['name']) . "'"
											. " WHERE `$this->uniqueField` = '$id'";
								$sql = $this->dbi->db->query($qry);

							} else {
								echo "Warning: $fieldName wasn't a valid uploaded file<BR>\n";
							}
						}
					}
			}


		}

		function deleteForm($id) {
			$modes = split(",", $this->mode);
			if (!in_array("delete", $modes)) return FALSE;
			$sql = $this->dbi->db->query("DELETE FROM `$this->table` WHERE $this->uniqueField='$id'");
		}


		function searchForm($displayFieldsList) {
			$modes = split(",", $this->mode);
			if (!in_array("search", $modes)) return FALSE;

			$displayFields = split(",", $displayFieldsList);

			$action = $this->urlPrefix;
			$action .= (!empty($this->urlVars)) ? "?" . $this->urlVars . "&" : "/?";
			$action .= "$this->actionVariable=searchSubmit";

			echo "<H2>Search $this->title</H2>\n";

			$tmp = $this->urlPrefix;
			$tmp .= (!empty($this->urlVars)) ? "?" . $this->urlVars : "/?";
			echo "<LI><A HREF=\"$tmp\">Browse</A></LI><BR>\n";

			?>
			<B>%</B> wildcard<BR><BR>
			<FORM METHOD="post" ACTION="<?= $action ?>">
			<TABLE WIDTH="100%" BORDER=0 ALIGN="center">
			<?

			for($i=0; $i<$this->fieldCount; $i++) {
				$fieldName = $this->fields[$i];
				$this->value = $obj->$fieldName;
				if (in_array($fieldName, $displayFields)) {
					echo "<TR>";
						echo "<TD VALIGN=\"top\">" . ucwords($fieldName) ."</TD>";
						echo "<TD VALIGN=\"top\">";
							$this->text($fieldName, "", 30);

							echo " &nbsp; ";
							$this->radio("searchOp[$i]", "and", 0); echo " AND";

							echo " &nbsp; ";
							$this->radio("searchOp[$i]", "or", 1); echo " OR";

				
						echo "</TD>";
					echo "</TR>\n";
				}
			}
			?>
			<TR><TD COLSPAN=<?= $this->fieldCount ?> ALIGN="center"><INPUT TYPE="submit" VALUE="Search"></TD></TR>
			</TABLE>
			</FORM>
			<?
		}

		function searchFormSubmit($displayFieldsList) {
			$modes = split(",", $this->mode);
			if (!in_array("search", $modes)) return FALSE;

			$displayFields = split(",", $displayFieldsList);
			$c = count($displayFields);

			if ($this->version < 410) global $HTTP_POST_VARS;
			$post_vars = ($this->version <= 406) ? $HTTP_POST_VARS : $_POST;

			$searchOp = $post_vars['searchOp'];

			$start = intval($_GET['start']);
			$offset = intval($_GET['offset']);
			if ($offset == 0) $offset = 5;

			$qry = "SELECT `$this->uniqueField`,";
			for($i=0; $i<$c; $i++) {
				$qry .= "`" . $displayFields[$i] . "`";
				if ($i < $c - 1) $qry .= ",";
			}
			$qry .= " FROM `$this->table`";

			$qry_where = " WHERE 1";
			$sqry = $qry;

			echo "<H2>$this->title Search Results</H2>\n";

			$sfield = $_GET['field'];
			$sorder = $_GET['order'];
			$sord = (($sorder == "DESC") ? "ASC" : "DESC");

			$i = 0;
			$urlVars = "action=searchSubmit";
			foreach($post_vars as $var => $val) {
				if (!in_array($var, $displayFields)) continue;

				if (!empty($val)) {
					$qry_where .= (($searchOp[$i]) ? " AND " : " OR ");
					$qry_where .= "`$var` LIKE '" . $this->dbi->db->escape_string($val) . "'";
					$urlVars .= "&$var=" . urlencode($val);
				}
			}
			$qry .= $qry_where;

			$sort_type = $_GET['sort'];
			$orderBy = (($sort_type) ? "ORDER BY `$sfield` $sorder" : $this->sqlOrderBy);

			if ($qry == $sqry) {
				return TRUE;
			} else {
				$sql = $this->dbi->db->query("SELECT COUNT(*) FROM `$this->table` $qry_where");
				if ($sql && $this->dbi->db->num_rows($sql)) {
					list($total_rows) = $this->dbi->db->fetch_row($sql);
				} else {
					return TRUE;
				}

				$qry .= " $orderBy LIMIT $start,$offset";

				$sql = $this->dbi->db->query($qry);
				if ($sql) {
					$num_rows = $this->dbi->db->num_rows($sql);
					if ($num_rows) {
						echo "<TABLE WIDTH=100% CELLSPACING=0 BORDER=0 ALIGN=\"center\">\n";
						$x = intval($c / 2);
						echo "<TR>";
							echo "<TD COLSPAN=$x>";
							echo "records $start - " . ($start + ($num_rows - 1)) . ", $total_rows total";
							echo "</TD>";

							$y = $c - $x;
							echo "<TD COLSPAN=$y ALIGN=\"right\">";
								$pstart = $start - $offset;
								if ($pstart >= 0) {
									echo "<A HREF=\"$this->urlPrefix/?$urlVars&start=$pstart&sort=$sort_type&field=$sfield&order=$sorder\"><< Previous $offset</A>";
								} else {
									echo "<< Previous $offset";
								}
								echo " &nbsp; ";

								$nstart = $start + $offset;

								if ($nstart < $total_rows) {
									echo "<A HREF=\"$this->urlPrefix/?$urlVars&start=$nstart&sort=$sort_type&field=$sfield&order=$sorder\">Next $offset >></A>";
								} else {
									echo "Next $offset >>";
								}
							echo "</TD>\n";

						echo "</TR>\n";


						echo "<TR>\n";
						for($i=0; $i<$this->fieldCount; $i++) {
							$fieldName = $this->fields[$i];
							if (!in_array($fieldName, $displayFields)) continue;

							echo "<TD STYLE=\"border-bottom: 1px solid black;\">";
							if ($fieldName == $sfield) echo (($sorder == "DESC") ? "-" : "+") . " ";
							echo "<A HREF=\"$this->urlPrefix/?$this->actionVariable=searchSubmit&sort=1&start=$start&field=$fieldName&order=$sord\"><B>" . ucwords($fieldName) . "</B></A>";
							echo "</TD>\n";
						}
						echo "</TR>\n";

						echo "<TR><TD COLSPAN=$c>&nbsp;</TD></TR>\n";

						while ($obj = $this->dbi->db->fetch_object($sql)) {
							echo "<TR>\n";

							for($i=0; $i<$this->fieldCount; $i++) {
								$fieldName = $this->fields[$i];
								if (!in_array($fieldName, $displayFields)) continue;

								$var = $this->fields[$i];
								$text = $obj->$var;
								if  (strlen($text) > 40) {
									$text = substr($text, 0, 40);
									$text .= "...";
								}

								$fieldValue = $text;
								$fieldFunc = $this->fieldFuncs[$i];
								$fieldLink = $this->fieldLinks[$i];

								if (substr($fieldFunc, 0, 9) == "built-in:") {
									$fieldFunc = substr($fieldFunc, 9);
									$fieldValue = $this->customFunction($fieldFunc, $fieldValue);
								} else {
									if (function_exists($fieldFunc)) {
										$fieldValue = $fieldFunc($fieldValue);
									}
								}
								echo "<TD>";
								if (!empty($fieldLink)) {
									echo "<A HREF=\"";
									echo $this->fieldLinkify($i, $obj);
								}
                                
								if (!empty($fieldLink)) echo "\">";
	                                        
								echo htmlspecialchars($fieldValue);  
                                        
								if (!empty($fieldLink)) echo "</A>";
								echo "</TD>\n";
							}
							echo "</TR>\n";
						}
						echo "</TABLE>\n";
					} else {
						echo "No records found<BR>\n";
					}

				} else {
					echo "Error executing query<BR>\n";
					echo $this->dbi->db->error();
				}
			}

		}

		function customFunction($funcName, $value) {
			/* $func_args[2] is the first dynamic argument */
			$func_args = $this->fieldFuncArgs[$funcName];
			switch ($funcName) {
				case "boolean":
					if (!empty($func_args[3]))
						list($yes, $no) = split("/", $func_args[3]);
					else {
						$yes = "Yes";
						$no = "No";
					}
					return ($value) ? $yes : $no;
					break;

				case "date":
					$format = (empty($func_args[2])) ? "M d Y" : $func_args[2];
					return date($value, $format);
					break;

				case "timestamp":
					$format = (empty($func_args[3])) ? "M d Y" : $func_args[3];
					return formatTime($value, $format);
					break;

				case "table":
					list($field_table, $field_id, $field_name) = $this->fieldTables[$this->id];

					$qry = "SELECT `$field_name` FROM `$field_table` WHERE `$field_id`='" . $this->dbi->db->escape_string($value) . "'";
					$sql = $this->dbi->db->query($qry);
					if ($sql && $this->dbi->db->num_rows($sql)) {
						list($new_value) = $this->dbi->db->fetch_row($sql);
						return $new_value;
					} else {
						return $value;
					}

					break;

				default:
					return $value;
					break;
			}
		}


		function linkField($fieldName, $url) {
			$fieldID = $this->fieldIDFromName($fieldName);
			$this->fieldLinks[$fieldID] = $url;
		}

		function fieldLinkify($id, $obj) {
			$url = $this->fieldLinks[$id];

			$c = count($this->fields);
			for($i=0; $i<$c; $i++) {
				$field = $this->fields[$i];
				$url = str_replace('$' . $field, $obj->$field, $url);
			}
			return $url;
		}

		function displayForm($displayFieldsList, $start = -1, $offset = -1) {
			if ($start == -1) $start = $this->sqlStart;
			if ($offset == -1) $offset = $this->sqlOffset;
			$displayFields = split(",", $displayFieldsList);
			$modes = split(",", $this->mode);
			$rowColors = split(",", $this->rowColors);
			$rowColorNum = 0;
			$rowColorCount = count($rowColors);
			$c = count($displayFields);

			echo "<H2>$this->title</H2>\n";

			echo '<TABLE WIDTH="100%" BORDER=0 ALIGN="center" CELLSPACING=0 CELLPADDING=' . $this->cellpadding . '>';
			echo "<TR><TD COLSPAN=$c>\n";
			if (in_array("add", $modes)) {
				$action = $this->urlPrefix;
				$action .= (!empty($this->urlVars)) ? "?" . $this->urlVars . "&$this->actionVariable=add" : "/?$this->actionVariable=add";
				echo "<LI><A HREF=\"$action\">Add</A></LI>\n";
			}

			if (in_array("search", $modes)) {
				$action = $this->urlPrefix;
				$action .= (!empty($this->urlVars)) ? "?" . $this->urlVars . "&$this->actionVariable=search" : "/?$this->actionVariable=search";
				echo "<LI><A HREF=\"$action\">Search</A></LI>\n";
			}

			$alc = count($this->additionalLinks);
			for($i=0; $i<$alc; $i++) {
				echo "<LI>" . $this->additionalLinks[$i] . "</LI>\n";
			}

			?>
			</TD></TR>
			<TR><TD COLSPAN=<?= $this->fieldCount + 1 ?>>&nbsp;</TD></TR>
			<TR>
			<?
			$sfield = $_GET['field'];
			$sorder = $_GET['order'];
			$sord = (($sorder == "DESC") ? "ASC" : "DESC");

			for($i=0; $i<$this->fieldCount; $i++) {
				$fieldName = $this->fields[$i];
				if (!in_array($fieldName, $displayFields)) continue;

				echo "<TD STYLE=\"border-bottom: 1px solid black;\">";
				if ($fieldName == $sfield) echo (($sorder == "DESC") ? "-" : "+") . " ";
				echo "<A HREF=\"$this->urlPrefix/?sort=1&start=$start&field=$fieldName&order=$sord\"><B>" . ucwords($fieldName) . "</B></A>";
				echo "</TD>\n";
			}
			echo "<TD STYLE=\"border-bottom: 1px solid black;\">&nbsp;</TD>\n";
			echo "</TR>";

			$sql = $this->dbi->db->query("SELECT COUNT(*) FROM `$this->table` $this->sqlWhere");
			@list($totalRows) = $this->dbi->db->fetch_row($sql);

			$qry = "SELECT `$this->uniqueField`,";
			for($i=0; $i<$c; $i++) {
				$qry .= "`" . $displayFields[$i] . "`";
				if ($i < $c - 1) $qry .= ",";
			}

			$orderBy = (($_GET['sort']) ? "ORDER BY `$sfield` $sorder" : $this->sqlOrderBy);
			$qry .= " FROM `$this->table` $orderBy LIMIT $start,$this->sqlOffset";

			$sql = $this->dbi->db->query($qry);

			if ($sql && $this->dbi->db->num_rows($sql)) {
				$pstart = $start - $this->sqlOffset;
				$nstart = $start + $this->sqlOffset;

				$action = $this->urlPrefix;
				$action .= (!empty($this->urlVars)) ? "?" . $this->urlVars . "&" : "/?";

				if ($pstart >= 0 || $nstart <= $totalRows) {
					echo "<TR><TD COLSPAN=" . ($this->fieldCount + 1) . " ALIGN=\"right\" NOWRAP>";
					if ($pstart >= 0) 
						echo "<A HREF=\"" . $action . "start=$pstart&field=$sfield&order=$sorder\"><< Previous $this->sqlOffset</A>";
					else
						echo "<< Previous $this->sqlOffset";
					echo " &nbsp; ";

					if ($nstart <= $totalRows)
						echo "<A HREF=\"" . $action . "start=$nstart&field=$sfield&order=$sorder\">Next $this->sqlOffset >></A>";
					else
						echo "Next $this->sqlOffset >>";
					echo "</TD></TR>";
				}

				echo "<TR><TD COLSPAN=" . ($this->fieldCount + 1) . ">&nbsp;</TD></TR>";

				$tmp = $this->uniqueField;
				while ($obj = $this->dbi->db->fetch_object($sql)) {
					echo "<TR BGCOLOR=\"" . $rowColors[$rowColorNum] . "\">";
					$rowColorNum++;
					if ($rowColorNum == $rowColorCount) $rowColorNum = 0;

					for($i=0; $i<$this->fieldCount; $i++) {

						$fieldName = $this->fields[$i];
						if (!in_array($fieldName, $displayFields)) continue;

						$this->id = $i;
						$this->value = $fieldName;

						echo "<TD ALIGN=\"left\" VALIGN=\"top\">";
						$fieldValue = $obj->$fieldName;
						$fieldFunc = $this->fieldFuncs[$i];
						$fieldLink = $this->fieldLinks[$i];

						if (substr($fieldFunc, 0, 9) == "built-in:") {
							$fieldFunc = substr($fieldFunc, 9);
							$fieldValue = $this->customFunction($fieldFunc, $fieldValue);
						} else {
							if (function_exists($fieldFunc)) {
								$fieldValue = $fieldFunc($fieldValue);
							}
						}

						if (!empty($fieldLink)) {
							echo "<A HREF=\"";
							echo $this->fieldLinkify($i, $obj);
						}

						if (!empty($fieldLink)) echo "\">";

						echo htmlspecialchars($fieldValue);

						if (!empty($fieldLink)) echo "</A>";

						echo "</TD>";
					}
					$objID = $obj->$tmp;
					echo "<TD ALIGN=\"center\" NOWRAP>";
						if (in_array("edit", $modes))
							echo "<A HREF=\"" . $action . "$this->actionVariable=edit&$this->uniqueField=$objID&start=$start\">edit</A> &nbsp; ";
						if (in_array("delete", $modes))
							echo "<A HREF=\"" . $action . "$this->actionVariable=delete&$this->uniqueField=$objID&start=$start\">delete</A>";
					echo "</TD>";
					echo "</TR>";
				}
			} else {
				echo "<TR><TD COLSPAN=" . ($this->fieldCount + 1) . " ALIGN=\"center\"><I>No records found</I></TD></TR>";
			}

			echo "</TABLE>";
		}

		function viewRecord($displayFieldsList, $id, $mapField = NULL) {
			$modes = split(",", $this->mode);
			if (!in_array("view", $modes)) return FALSE;

			$displayFields = split(",", $displayFieldsList);
			$c = count($displayFields);

			$qry = "SELECT ";
			for($i=0; $i<$c; $i++) {
				$qry .= "`" . $displayFields[$i] . "`";
				if ($i < $c - 1) $qry .= ",";
			}
			$qry .= " FROM `$this->table` WHERE `$this->uniqueField`='" . $this->dbi->db->escape_string($id) . "'";
			$sql = $this->dbi->db->query($qry);
			if ($sql && $this->dbi->db->num_rows($sql)) {
				$obj = $this->dbi->db->fetch_object($sql);

				echo "<H2>$this->title - " . (($mapField) ? htmlspecialchars($obj->$mapField) : htmlspecialchars($id)) . "</H2>\n";

				echo '<TABLE WIDTH="100%" BORDER=0 ALIGN="center">' . "\n";
				echo "<TR><TD>\n";
				for($i=0; $i<$this->fieldCount; $i++) {
					$field = $this->fields[$i];

					if (!in_array($field, $displayFields)) continue;

					$fieldValue = $obj->$field;
					$fieldFunc = $this->fieldFuncs[$i];

					if (substr($fieldFunc, 0, 9) == "built-in:") {
						$fieldFunc = substr($fieldFunc, 9);
						$fieldValue = $this->customFunction($fieldFunc, $fieldValue);
					} else {  
						if (function_exists($fieldFunc)) {
							$fieldValue = $fieldFunc($fieldValue);
						}
					}

					$text = htmlspecialchars($fieldValue);
					$text = ((empty($text)) ? "<I>empty</I>" : $text);

					$l = strlen($text);

					$title = htmlspecialchars(ucwords($field));

					if ($l < 15) {
						echo "<B>$title:</B>\n";
						echo "$text\n<BR>";
					} else {
						echo "<BR><B>$title</B>\n";
						echo "<BR>\n$text\n";
					}
				}
				echo "</TD></TR>\n";

				

				echo "</TABLE>\n";
			} else {
				echo "<H2>$this->title</H2>\n";
				echo "Invalid Record<BR>\n";
			}
		}


		function formHandler($displayFields = -1, $addFields = -1, $editFields = -1, $searchFields = -1, $viewFields = -1) {
			$allFields = join(",", $this->fields);

			if ($displayFields == -1) $displayFields = $allFields;
			if ($addFields == -1) $addFields = $allFields;
			if ($editFields == -1) $editFields = $allFields;
			if ($searchFields == -1) $searchFields = $allFields;
			if ($viewFields == -1) $viewFields = $allFields;


			switch ($_GET[$this->actionVariable]) {

			case "add":
				$this->addForm($addFields);
				return FALSE;
				break;

			case "addSubmit":
				$this->addFormSubmit($addFields);
				unset($_GET[$this->actionVariable]);
				return TRUE;
				break;

			case "edit":
				$this->editForm($editFields, $_GET[$this->uniqueField]);
				break;

			case "editSubmit":
				$this->editFormSubmit($editFields, $_GET[$this->uniqueField]);
				unset($_GET[$this->actionVariable]);
				return TRUE;
				break;

			case "delete":
				$this->deleteForm($_GET[$this->uniqueField]);
				unset($_GET[$this->actionVariable]);
				return TRUE;
				break;

			case "search":
				$this->searchForm($searchFields);
				return FALSE;
				break;

			case "searchSubmit":
				$this->searchFormSubmit($searchFields);
				unset($_GET[$this->actionVariable]);
				return FALSE;
				break;

			case "view":
				$this->viewRecord($viewFields, $_GET[$this->uniqueField]);
				break;

			default:
				$this->displayForm($displayFields, intval($_GET['start']), intval($_GET['offset']));
				return FALSE;
				break;
			}
		}

		function selectSource($field_name, $table, $valueField, $dispField, $extra = "") {
			$sql = $this->dbi->db->query("SELECT `$valueField`,`$dispField` FROM `$table` $extra");
			unset($rv);
			$rv['default'] = "-- Select One --";

			$fieldID = $this->fieldIDFromName($field_name);

			$this->fieldTables[$fieldID] = array($table, $valueField, $dispField, $extra);

			if ($sql) {
				while(list($k, $v) = $this->dbi->db->fetch_row($sql)) $rv[$k] = $v;
			}
			return $rv;
		}

		function additionalLink($title, $url, $extra = "") {
			$this->additionalLinks[] = "<A $extra HREF=\"$url\">$title</A>\n";

			echo "$qry";
		}



	}
?>
