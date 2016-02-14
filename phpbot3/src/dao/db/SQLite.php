<?php
 /**
 * SQLite database stuff.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: SQLite.php,v 1.3 2006/02/06 01:25:09 mmr Exp $
 */
require_once("CouldNotConnectException.php");
require_once("NotConnectedException.php");
require_once("InvalidQueryException.php");

final class SQLite {
	private $link = null;

	/**
	 * Constructor.
	 */
	public function __construct($configFile) {
	    $this->connect($configFile);
	}

	/**
	 * Destructor.
	 */
	public function __destruct() {
		$this->disconnect();
	}

	/**
	 * Connect to the database.
	 * @param $configFile configuration file.
	 */
	private function connect($configFile) {
		if ($this->isConnected()) {
			throw new CouldNotConnectException("Already connected to DB.");
		}

		require_once ($configFile);
		$error = "";
		if ($this->link = sqlite_popen(DBConfig::$FILE, DBConfig::$MODE, $error)) {
			return true;
		} else {
			throw new CouldNotConnectException($error);
		}
	}

	/**
	 * @return <code>true</code> if is already connected, <code>false</code> otherwise.
	 */
	private function isConnected() {
		return !is_null($this->link);
	}

	/**
	 * Checks if the given query is valid, also checks for connectivity with the database.
	 * @param $query the query to check.
	 * @return <code>true</code> if the query is valid, <code>false</code> if not.
	 * @throws NotConnectedException if is not connected to the database.
	 * @throws InvalidQueryException if the given query is not valid.
	 */
	private function checkQuery($query) {
		if (!$this->isConnected()) {
			throw new NotConnectedException($query);
		}
		if (empty($query)) {
			throw new InvalidQueryException($query);
		}
		return true;
	}

	/**
	 * Executes a query that might return just one row.
	 * @param $query the query.
	 * @return an array with the requested row data.
	 */
	public function singleQuery($query) {
		checkQuery($query);

		#echo "$query\n";
		$ret = sqlite_array_query($query, $this->link, SQLITE_ASSOC);
		if (count($ret)) {
			return $ret[0];
		}
	}

	/**
	 * Executes a sql query and return its result.
	 * @param $query the query.
	 * @return a multidimensional array with all the data requested.
	 */
	public function query($query) {
		checkQuery($query);

		#$result = sqlite_unbuffered_query($query, $this->link);
		$result = sqlite_query($query, $this->link);

		if (!$result) {
			throw new Exception("Query Failed: '$query'");
		}

		$num = sqlite_num_rows($result);

		if ($num > 0) {
			for ($i = 0; $i < $num; $i ++) {
				$rows[$i] = sqlite_fetch_array($result, SQLITE_ASSOC);
			}

			return $rows;
		} else {
			return sqlite_last_insert_rowid($this->link);
		}
	}

	/**
	 * Ends the connection.
	 */
	private function disconnect() {
		if (!is_null($this->link)) {
			return sqlite_close($this->link);
		}

		return false;
	}

	/**
	 * Format data to be used in the db, doing scaping and stuff.
	 * @param $var the data to be formatted.
	 * @return a string with the formatted data or a NULL string if the data to be formatted is empty.
	 */
	public static function format($var) {
		$var = trim($var);

		if (strlen($var) == 0 || is_null($var)) {
			return 'NULL';
		}

		return "'" . addslashes($var) . "'";
	}
}
?>
