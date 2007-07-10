<?php
/**
 * User Data Access Object.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: UserDAO.php,v 1.2 2006/02/06 01:18:28 mmr Exp $
 */
require_once("DAO.php");
final class UserDAO extends DAO {
	private static $instance = null;

	/**
	 * Private constructor, so this class cannot be instantiated directly.
	 */
	protected function __construct() {
        parent::__construct();
	}

	/**
	 * Returns unique instance of this DAO.
	 * @return unique instance of this DAO.
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new UserDAO();
		}
		return self::$instance;
	}

	/**
	 * Insert data.
	 * @param $vo data.
	 */
	public function insert(UserVO $vo) {
		$query  = "INSERT INTO user (usr_name, usr_nick, chn_id) VALUES (";
		$query .= SQLite::format($vo->getName()) . ", ";
		$query .= SQLite::format($vo->getNick()) . ", ";
		$query .= SQLite::format($vo->getChannel()->getId());
		$query .= ")";

		echo $query;
	}

	/**
	 * Update data.
	 * @param $vo data.
	 */
	public function update(UserVO $vo) {
		$query  = "UPDATE user SET ";
		$query .= "usr_name = " . SQLite::format($vo->getName()) . ", ";
		$query .= "usr_nick = " . SQLite::format($vo->getNick()) . ", ";
		$query .= "chn_id   = " . SQLite::format($vo->getChannel()->getId());
		$query .= " WHERE usr_id = " . SQLite::format($vo->getId());
	}
}
?>
