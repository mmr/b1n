<?php
/**
 * IRC Client Layer.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: Irc.php,v 1.1.1.1 2006/02/05 22:42:08 mmr Exp $
 */
class Irc {
	private $connection = null;
	private $eventListeners = null;

	/**
	 * Adds an event listener to the event listeners collection.
	 * @param $eventListener event listener to add.
	 */
	public function addEventListener(IrcEventListener $eventListener) {
		if ($this->eventListeneners == null) {
			$this->eventListeners = array();
		}
		$eventListeners[] = $eventListener;
	} 

	/**
	 * Notify event listeners of an event.
	 * @param $event event to notify.
	 */
	private function notifyEventListeners(IrcEvent $event) {
		foreach ($this->eventListeners as $eventListener) {
			$eventListener->handleIrcEvent($event);
		}
	}
}
?>