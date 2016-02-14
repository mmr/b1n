<?php
/**
 * Abstraction of Data Access Object, all DAOs should extend it.
 * This implementation uses SQLite RDMBS.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: DAO.php,v 1.3 2006/02/06 01:18:28 mmr Exp $
 */
require_once("db/SQLite.php");
abstract class DAO {
	private $ds = null;

	protected function __construct() {
        echo "Connecting to database...\n";
		$this->ds = new SQLite("DBConfig.php");
	}
}
?>
