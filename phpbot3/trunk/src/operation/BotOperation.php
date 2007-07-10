<?
/**
 * Abstract Bot Operation.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: BotOperation.php,v 1.1.1.1 2006/02/05 22:42:08 mmr Exp $
 */
abstract class BotOperation {
	private $trigger = null;

	/**
	 * Construtor.
	 * @param $trigger operation trigger.
	 */
	public function __construct($trigger) {
		$this->trigger = $trigger;
	}

	/**
	 * Test the event against the trigger to check if the operation is to be fired.
	 * @return <code>true</code> if the operation did trigger, <code>false</code> otherwise.
	 */
	final public function isTriggered(BotEvent $event) {
		$match = array();
		$message = $event->getMessage();
		if (preg_match("#^(\s*".$this->trigger."(\s+|$))#i", $message, $match)) {
			$message = substr($message, strlen($match[1]));
			$event->setMessage($message);
			return true;
		}
		return false;
	}

	/**
	 * Returns the trigger of this operation.
	 * @return the trigger of this operation.
	 */
	final public function getTrigger() {
		return $this->trigger;
	}

	/**
	 * Reply of the operation.
	 * @param $event BotEvent that might have triggered the operation.
	 */
	abstract public function reply(BotEvent $event);
}
?>