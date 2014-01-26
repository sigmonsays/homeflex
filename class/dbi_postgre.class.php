<?
	class dbi_postgre {
		var $parent;

		var $conn;				// link resource

		var $host, $user, $pass, $database;		// connection info

		var $sql;				// most recent return of query()
		var $fields_list;		// used for mysql_num_fields(), mysql_field_name(), etc

		/* constructor */
		function dbi_postgre(&$parent) {
			$this->conn = NULL;
			$this->parent = $parent;
			$this->database = NULL;
		}

		function build_connection_string($host = "localhost", $user = "root", $password = "", $database = "template1") {
			$conn_str = "dbname=$database ";

			if ($host != "localhost") 	$conn_str .= " host=" . $host . " port=5432";
			if ($user != "root") 		$conn_str .= " user=" . $user;
			if (!empty($pass)) 			$conn_str .= " password=" . $pass;

			return $conn_str;
		}

		function affected_rows($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$r = pg_affected_rows($sql);
			return $r;
		}

		function connect($host = "localhost", $user = "root", $pass = "", $database = "template1") {

			//host=sheep port=5432 dbname=template1 user=lamb password=bar";

			$this->host = $host;
			$this->user = $user;
			$this->pass = $pass;
			$this->database = $database;

			$conn_str = $this->build_connection_string($host, $user, $pass, $database);

			$conn = pg_connect($conn_str);

			if (!$conn) return FALSE;
			$this->conn = $conn;
			return TRUE;
		}

		function close() {
			$r = pg_close($this->conn);
			$this->conn = NULL;
			return $r;
		}

		function select_db($db) {
			if (!$this->conn) return FALSE;

			$this->close();

			$conn_str = $this->build_connecion_string($this->host, $this->user, $this->pass, $db);

			if (!$this->connect($this->host, $this->user, $this->pass, $db)) return FALSE;

			$this->database = $db;

			return TRUE;
		}

		function query($qry) {
			$this->sql = $sql = pg_query($qry, $this->conn);
			return $sql;
		}

		function db_query($db, $qry) {
			echo "WARNING: " . __FILE__ . ": " . __FUNCTION__ . " is not implemented\n";
		}


		function error() {
			return pg_error($this->conn);
		}

		function list_tables($database) {
			echo "WARNING: " . __FILE__ . ": " . __FUNCTION__ . " is not implemented\n";
		}

		function fetch_row($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$row = pg_fetch_row($sql);
			return $row;
		}

		function fetch_object($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$obj = pg_fetch_object($sql);
			return $obj;
		}

		function fetch_assoc($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$ass = pg_fetch_assoc($sql);
			return $ass;
		}

		function num_rows($sql = NULL) {
			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$num_rows = pg_num_rows($sql);
			return $num_rows;
		}

		function escape_string($str) {
			return pg_escape_string($str);
		}

		function insert_id($sql = NULL) {
			echo "WAH@!!!! FIX ME!!<BR>\n";

			if ($sql == NULL) $sql = $this->sql;
			if (!$sql) return FALSE;

			$oid = pg_last_oid ($sql);
			$result_for_id = pg_query($this->conn, "SELECT $column_name FROM $table_name WHERE oid=$oid");

			if(pg_num_rows($result_for_id)) {
				$id=pg_fetch_array($result_for_id,0,PGSQL_ASSOC);
			}
   		return $id[$column_name];
		}


		function list_fields($table) {
			$this->fields_list = $res = mysql_list_fields($this->database, $table);
			return $res;
		}

		function num_fields() {
			if (!$this->fields_list) return FALSE;
			return mysql_num_fields($this->fields_list);
		}

		function field_name($index) {
			return mysql_field_name($this->fields_list, $index);
		}
		
	}

?>
