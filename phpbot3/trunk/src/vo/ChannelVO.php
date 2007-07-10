<?php
/**
 * Channel Value Object.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: ChannelVO.php,v 1.3 2006/02/06 01:18:28 mmr Exp $
 */
require_once("VO.php");
final class ChannelVO extends VO {
	private $name = null;
	private $server = null;

	/**
	 * Constructor.
	 * @param $name the name of the channel.
	 * @param $server the server where this channel is.
	 */
	public function __construct($name, ServerVO $server) {
		$this->name = $name;
		$this->server = $server;
	}

	/**
	 * Returns the name of the channel.
	 * @return the name of the channel.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name of the channel.
	 * @param $name the name of the channel.
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the server.
	 * @return the server.
	 */
	public function getServer() {
		return $this->server;
	}

	/**
	 * Sets the server where this channel is.
	 * @param $server the server where this channel is.
	 */
	public function setServer(ServerVO $server) {
		$this->server = $server;
	}
}
?>
