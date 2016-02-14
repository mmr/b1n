<?php
/**
 * IRC Event.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: IrcEvent.php,v 1.1.1.1 2006/02/05 22:42:08 mmr Exp $
 */
class IrcEvent {
	public static $TYPE_GOT_MESSAGE = 0;
	public static $TYPE_USER_JOINED = 1;
	public static $TYPE_USER_QUIT = 2;
	
	private $type = null;
	private $content = null;
	
	/**
	 * Constructor.
	 * @param $type event type.
	 * @param $content map with event content data.
	 */
	public function __construct($type, $content) {
		$this->type = $type;
		$this->content = $content;
	}

	/**
	 * Returns the event type.
	 * @return the event type.
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the event type.
	 * @param $type the event type.
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Returns a map with the content of the event.
	 * @return a map with the content of the event.
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Sets a map with the content of the event.
	 * @param $event map with the content of the event.
	 */
	public function setContent($content) {
		$this->content = $content;
	}
}
?>