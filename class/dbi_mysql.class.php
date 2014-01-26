<?
	class dbi_mysql {
		var $parent;

		var $database;
		var $conn;				// link resource
		var $sql;				// most recent return of query()
		var $fields_list;		// used for mysql_num_fields(), mysql_field_name(), etc
		var $parent;

		/* constructor */
		function dbi_mysql() {
			$this->conn = NULL;
			$this->database = NULL;
		}

		function affected_rows($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$r = mysql_affected_rows($sql);
			return $r;
		}

		function connect($host = "localhost", $user = "root", $pass = "") {
			@$conn = mysql_connect($host, $user, $pass);
			if (!$conn) return FALSE;
			$this->conn = $conn;
			return TRUE;
		}

		function close() {
			$r = mysql_close($this->conn);
			$this->conn = NULL;
			return $r;
		}

		function select_db($db) {
			@$r = mysql_select_db($db, $this->conn);
			if ($r) $this->database = $db;

			return $r;
		}

		function query($qry) {
			$this->sql = $sql = mysql_query($qry, $this->conn);

			$this->parent->do_query_stats($sql, $qry);
			return $sql;
		}

		function db_query($db, $qry) {
			$this->sql = $sql = mysql_db_query($db, $qry, $this->conn);

			$this->parent->do_query_stats($sql, $qry);
			return $sql;
		}


		function error() {
			if (!$this->conn) return FALSE;
			return mysql_error($this->conn);
		}

		function list_tables($database) {
			$this->sql = $sql = mysql_list_tables($database, $this->conn);
			return $sql;
		}

		function fetch_row($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$row = mysql_fetch_row($sql);
			return $row;
		}

		function fetch_object($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$obj = mysql_fetch_object($sql);
			return $obj;
		}

		function fetch_assoc($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$ass = mysql_fetch_assoc($sql);
			return $ass;
		}

		function num_rows($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$num_rows = mysql_num_rows($sql);
			return $num_rows;
		}

		function escape_string($str) {
			return mysql_escape_string($str);
		}

		function insert_id() {
			return mysql_insert_id($this->conn);
		}


		function list_fields($table) {
			return mysql_list_fields($this->database, $table, $this->conn);
		}

		function num_fields($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			return mysql_num_fields($sql);
		}

		function field_name($sql = NULL, $index = 0) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;
			return mysql_field_name($sql, $index);
		}

		function field_table($sql = NULL, $index = 0) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;
			return mysql_field_table($sql, $index);
		}
	}

?>
