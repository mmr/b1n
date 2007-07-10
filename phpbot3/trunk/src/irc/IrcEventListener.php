<?php
/**
 * Interface for IRC Client Event Listeners.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: IrcEventListener.php,v 1.1.1.1 2006/02/05 22:42:08 mmr Exp $
 */
interface IrcEventListener {
	/** Handle given IrcEvent. */
	public function handleIrcEvent(IrcEvent $event);
}
?>