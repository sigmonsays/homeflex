<?
	class picture_navbar {

		var $dbi;

		var $current_node;

		var $table;

		/* constructor */
		function picture_navbar(&$dbi, $current_node = 0) {
			if (!$dbi || !$dbi->db) return FALSE;

			$this->dbi = $dbi;

			$this->current_node = $current_node;

			$this->table = "pictures_cat";

			return TRUE;
		}

		function getAllChildren($node) {
			$name = $this->getName($node);
			$ar = array($node => $name);
			$this->getAllChildrenRecurs($node, $ar);
			return $ar;
		}

		function getAllChildrenRecurs($node, &$ar) {

			$children = $this->getChildren($node);
			foreach($children as $id => $name) {
				$this->getAllChildrenRecurs($id, $ar);
				$ar[$id] = $name;
			}
		}

		/* returns a path array back to root node 0 */
		function getPath($node) {
			$path = array();

         $x = $node;

         for(;;) {
            $parent = $this->getParent($x);
            if ($parent->id == 0) break;
				$path[$parent->id] = $parent->name;
            $x = $parent->parent;
         }

			$path[0] = "Pictures";

			$rpath = array_reverse($path, TRUE);

			return $rpath;
		}

		function getDescription($node) {
			$qry = "SELECT description FROM $this->table WHERE id='$node'";

			$this->dbi->db->query($qry);

			if (!$this->dbi->db->num_rows()) return NULL;

			list($description) = $this->dbi->db->fetch_row();
			$description = stripslashes($description);
			return $description;
		}

		function getName($node) {
			$qry = "SELECT name FROM $this->table WHERE id='$node'";

			$this->dbi->db->query($qry);

			if (!$this->dbi->db->num_rows()) return NULL;

			list($name) = $this->dbi->db->fetch_row();
			return $name;

		}

		function getChildren($node) {
			$children = array();

			$qry = "SELECT id,name FROM $this->table WHERE parent='$node' ORDER BY `name` ASC";

			$this->dbi->db->query($qry);

			if (!$this->dbi->db->num_rows()) return $children;

			while (list($id, $name) = $this->dbi->db->fetch_row()) {
				$children[$id] = $name;
			}
			return $children;
		}


		function getParent($node) {

			$qry = "SELECT * FROM $this->table WHERE id='$node'";

			$this->dbi->db->query($qry);

			if (!$this->dbi->db->num_rows()) return $parent;

			$parent = $this->dbi->db->fetch_object();
			return $parent;
		}

		function getParentID($node) {
			$qry = "SELECT parent FROM $this->table WHERE id='$node'";

			$this->dbi->db->query($qry);

			if (!$this->dbi->db->num_rows()) return $parent;

			list($parent) = $this->dbi->db->fetch_row();
			return $parent;
		}

		

	}
?>
