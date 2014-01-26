<?

	/* this is just a wrapper class */
	class dbi {
		var $db, $class;
		var $good_query_count, $bad_query_count;

		var $save_queries, $queries;
		
		/* constructor */
		function dbi($dbt) {

			$this->class = $class = 'dbi_' . $dbt;
			if (!class_exists($class)) return FALSE;

			$this->good_query_count = 0;
			$this->bad_query_count = 0;
			$this->save_queries = FALSE;

			$this->db = new $class();

			$this->db->parent = &$this;

			$this->queries = array();

			return TRUE;
		}

		function get_queries() {
			return $this->db->parent->queries;
		}

		function save_queries($x) {
			$this->db->parent->save_queries = $x;
		}

		function save_query($qry) {
//			if (!$this->save_queries) return FALSE;
			$this->db->parent->queries[] = $qry;
			return TRUE;
		}

		function inc_good_query() {
			$this->good_query_count++;
		}

		function inc_bad_query() {
			$this->bad_query_count++;
		}

		function good_query_count() {
			return $this->db->parent->good_query_count;
		}

		function bad_query_count() {
			return $this->db->parent->bad_query_count;
		}

		function do_query_stats($sql, $qry) {

			if ($sql) {
				$this->inc_good_query();
			} else {
				$this->inc_bad_query();
			}

			if ($this->db->parent->save_queries) {
				$this->save_query($qry);
			}

		}



	}

?>
