<?php
/**
 * Server Value Object.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: ServerVO.php,v 1.2 2006/02/06 01:18:28 mmr Exp $
 */
require_once("VO.php");
final class ServerVO extends VO {
	private $name = null;
	private $url = null;

	/**
	 * Constructor.
	 * @param $name the name of the server.
	 * @param $url the url to the server.
	 */
	public function __construct($name, $url) {
		$this->name = $name;
		$this->url = $url;
	}

	/**
	 * Returns the name of the server.
	 * @return the name of the server.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name of the server.
	 * @param $name the name of the server.
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the url of the server.
	 * @return the url of the server.
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Sets the url of the server.
	 * @param $url the url of the server.
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
}
?>
