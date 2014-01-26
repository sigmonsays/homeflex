<?
/*
	This is a simple class to display data from a mysql query

	a sample invocation (least amount of code)

	$qry = "SELECT * FROM users LEFT JOIN groups on users.gid = groups.id";

	$table = new sqlTable($qry);

	$table->display();


*/

	class sqlTable { 
		var $dbi;

		var $qry;
		var $limit,$offset;
		var $fieldCount, $fieldNames;
		var $sql;

		var $title;

		/* constructor */
		function sqlTable(&$dbi, $qry) {

			$this->dbi = $dbi;

			if (!$this->dbi || !$this->dbi->db) return FALSE;

			$this->qry = $qry;
			$this->limit = $this->offset = 0;

			$sql = $dbi->db->query($qry);
			if (!$sql) return FALSE;

			$this->title = $dbi->db->field_table($sql, 0);

			$this->sql = $sql;

			$fc = $dbi->db->num_fields();
			$this->fieldCount = $fc;

			$this->fieldNames = array();
			for($i=0; $i<$fc; $i++) {
				$defName = $dbi->db->field_name($sql, $i);
				$this->setFieldName($i, $defName);
			}

			return TRUE;
		}

		function setFieldName($i, $name) {
			if ($i > $this->fieldCount) return FALSE;
			$this->fieldNames[$i] = $name;
			return TRUE;
		}

		function getFieldName($i) {
			if ($i > $this->fieldCount) return FALSE;
			return $this->fieldNames[$i];	
		}

		function display() {

			if (!$this->sql) return FALSE;

			echo "<h2>" . $this->title . "</h2>\n";

			echo "<table>\n";

			if ($this->offset) {
				
			}

			/* table column heading */
			echo "<tr>\n";
			foreach($this->fieldNames as $i => $title) {
				echo "<td><b>" . $this->getFieldName($i) . "</b></td>\n";
			}
			echo "</tr>\n";

			while ($row = $this->dbi->db->fetch_row($this->sql)) {

				echo "<tr>\n";
				for($i=0; $i<$this->fieldCount; $i++) {
					echo "<td>" . $row[$i] . "</td>\n";
				}
				echo "</tr>\n";
			}

			echo "</table>\n";
		}
	}
?>
