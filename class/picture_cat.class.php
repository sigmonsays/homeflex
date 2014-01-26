<?
	class picture_cat {
		var $dbi;

		var $table;

		// wether or not the row exists
		var $row_exists;

		// db fields
		var $id, $parent, $name, $picture, $description;

		var $fields;

		/* model constructor */
		function picture_cat(&$dbi, $id = 0) {
			if (!$dbi || !$dbi->db) return FALSE;
			$this->dbi = $dbi;

			$this->id = 0;
			$this->row_exists = FALSE;
			$this->table = "pictures_cat";

			$this->fields = array(
				'id', 'parent', 'name', 'picture', 'description'
			);

			if ($id > 0) {
				$dbi->db->query("SELECT * FROM `$this->table` WHERE id='$id'");
				if (!$dbi->db->num_rows()) return FALSE;

				$row = $this->dbi->db->fetch_object();
				$this->row_exists = TRUE;
				foreach($row as $k => $v) {
					$this->$k = $v;
				}
				$this->id = $id;
			}
			return TRUE;
		}

		function build_update_query() {
			$qry = "UPDATE `$this->table` SET ";
			$c = count($this->fields);
			for($i=0; $i<$c; $i++) {
				$field = $this->fields[$i];
				$qry .= " `$field` = '" . mysql_escape_string($this->$field) . "'";
				if ($i < $c - 1) $qry .= ", ";
			}
			$qry .= " WHERE id='$this->id'";
			return $qry;
		}

		function build_insert_query() {
			$qry = "INSERT INTO `$this->table` ( ";
			$c = count($this->fields);

			for($i=0; $i<$c; $i++) {
				$field = $this->fields[$i];
				$qry .= "`$field`";
				if ($i < $c - 1) $qry .= ", ";
			}

			$qry .= ") VALUES (";
			for($i=0; $i<$c; $i++) {
				$field = $this->fields[$i];
				$qry .= "'" . mysql_escape_string($this->$field) . "'";
				if ($i < $c - 1) $qry .= ", ";
			}
			$qry .= ")";
			return $qry;
		}


		function check() {
			$this->errors = array();
			if (empty($this->name)) $this->errors[] = "Empty name";
			if (empty($this->picture)) $this->errors[] = "Empty picture";
			if (count($this->errors)) return FALSE;
			return TRUE;
		}

		function save() {

			if (!$this->check()) return FALSE;

			if (!$this->row_exists) {
				$qry = $this->build_insert_query();
				if (!$this->dbi->db->query($qry)) return FALSE;
				$this->id = $this->dbi->db->insert_id();
				$this->row_exists = TRUE;
				return TRUE;
			}

			$qry = $this->build_update_query();
			if (!$this->dbi->db->query($qry)) return FALSE;

			return TRUE;
		}

		function delete() {
			$this->dbi->db->query("DELETE FROM `$this->table` WHERE id='$this->id'");
			$this->row_exists = FALSE;
			$this->id = 0;
			return TRUE;
		}
	}
?>
