<?
/**
 * Say Operation.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: SayOperation.php,v 1.1.1.1 2006/02/05 22:42:08 mmr Exp $
 */
final class SayOperation extends BotOperation {
	/**
	 * Reply to operation.
	 * @param $event event that triggered this operation.
	 * @return BotMessage with the reply.
	 */
	public function reply(BotEvent $event) {
		if ($event->isPrivate()) {
			return;
		}

		$message = $event->getMessage();
		$reply = trim($message);

		if (!empty ($reply)) {
			return new BotMessage($event->getDestination(), $reply);
		}
	}
}
?>