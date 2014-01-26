<?
	class picture {
		var $dbi;

		var $table;

		// unique image prefix
		var $uid;

		// wether or not the row exists
		var $row_exists;

		// db fields
		var $id, $name, $category, $date_added, $picture, $thumbnail, $description;
		var $width, $height, $vwidth, $vheight, $view_count;

		var $fields;

		/* model constructor */
		function picture(&$dbi, $id = 0) {
			if (!$dbi || !$dbi->db) return FALSE;
			$this->dbi = $dbi;

			$this->id = 0;
			$this->row_exists = FALSE;
			$this->table = "pictures";
			$this->uid = $this->generateImageUID();

			$this->fields = array(
				'id', 'name', 'category', 'date_added', 'picture', 'thumbnail', 'description',
				'width', 'height', 'vwidth', 'vheight', 'view_count'
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

//		i guess I will allow images to be in the "Root" folder
//			if (empty($this->category)) $this->errors[] = "Empty category";

			if (empty($this->date_added)) $this->errors[] = "Empty date added";
			if (empty($this->picture)) $this->errors[] = "Empty picture";
			if (empty($this->thumbnail)) $this->errors[] = "Empty thumbnail";

			if (empty($this->width)) $this->errors[] = "Empty width";
			if (empty($this->height)) $this->errors[] = "Empty height";


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

			$pic = PICTURES_LOCAL_PATH . "/" . $this->picture;
			if (file_exists($pic)) unlink($pic);

			$pic = PICTURES_LOCAL_PATH . "/" . $this->thumbnail;
			if (file_exists($pic)) unlink($pic);

			$this->dbi->db->query("DELETE FROM `$this->table` WHERE id='$this->id'");
			$this->row_exists = FALSE;
			$this->id = 0;
			return TRUE;
		}

		function generateImageUID() {
			$uid = getmypid() . "_" . time() . "_";
			return $uid;
		}

		function generateThumbnail() {

			$dest = PICTURES_LOCAL_PATH . "/" . $this->picture;

			if (file_exists($this->thumbnail) && !is_dir($this->thumbnail)) {
				unlink($this->thumbnail);
			}

			$pic = new picture_transform($dest);
			$img_thumb = $pic->thumbnail(150);

			$filename = "thumb_" . basename($this->picture, $ext) . ".png";

			$thumb = PICTURES_LOCAL_PATH . "/" . $filename;
			imagePNG($img_thumb, $thumb);
			$this->thumbnail = $filename;
			imageDestroy($img_thumb);
		}

		function handleUpload($file) {
			if (!is_uploaded_file( $_FILES[$file]['tmp_name'])) {
				return FALSE;
			}

			$filename = $this->uid . basename($_FILES[$file]['name']);
			$dest = PICTURES_LOCAL_PATH . "/" . $filename;

			if (!move_uploaded_file($_FILES[$file]['tmp_name'], $dest)) {
				return FALSE;
			}

			$ext = strrchr(basename($_FILES[$file]['name']), ".");

			$pic = PICTURES_LOCAL_PATH . "/" . $this->thumbnail;
			if (file_exists($pic) && !is_dir($pic)) unlink($pic);

			$pic = PICTURES_LOCAL_PATH . "/" . $this->picture;
			if (file_exists($pic) && !is_dir($pic)) unlink($pic);

			$this->picture = $filename;

			list($this->width, $this->height) = getimagesize($dest);

			// make thumbnail
			$this->generateThumbnail();


			$this->name = $_FILES[$file]['name'];
			$this->category = intval($_POST['node']);

			$this->date_added = date("Y-m-d H:i:s");

			$this->vwidth = 0;
			$this->vheight = 0;

			return TRUE;
		}


	}
?>
