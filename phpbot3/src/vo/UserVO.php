<?php
/**
 * User Value Object.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: UserVO.php,v 1.2 2006/02/06 01:18:28 mmr Exp $
 */
require_once("VO.php");
final class UserVO extends VO {
	private $nick = null;
	private $name = null;
	private $channel = null;

	/**
	 * Constructor.
	 * @param $nick the nick of the user.
	 * @param $name the name of the user.
	 * @param $channel the channel the user is on.
	 */
	public function __construct($nick, $name, $channel) {
		$this->nick = $nick;
		$this->name = $name;
		$this->channel = $channel;
	}

	/**
	 * Returns the nick of the user.
	 * @return the nick of the user.
	 */
	public function getNick() {
		return $this->nick;
	}

	/**
	 * Sets the nick of the user.
	 * @param $nick the nick of the user.
	 */
	public function setNick($nick) {
		$this->nick = $nick;
	}

	/**
	 * Returns the name of the user.
	 * @return the name of the user.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name of the user.
	 * @param $name the name of the user.
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the channel this user is currently on.
	 * @return the channel this user is currently on.
	 */
	public function getChannel() {
		return $this->channel;
	}

	/**
	 * Sets the channel this user is currently on.
	 * @param $channel the channel to set.
	 */
	private function setChannel(ChannelVO $channel) {
		$this->channel = $channel;
	}
}
?>
