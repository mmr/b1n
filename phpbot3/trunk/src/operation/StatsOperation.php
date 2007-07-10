<?
/**
 * Statistics about the bot Operation.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: StatsOperation.php,v 1.1.1.1 2006/02/05 22:42:08 mmr Exp $
 */
final class StatsOperation extends BotOperation {
	/**
	 * Reply to the operation.
	 * @param $event event that triggered the operation.
	 * @return BotMessage with bot statistics.
	 */
	public function reply(BotEvent $event) {
		$sender = $event->getSender();
		$uptime = time() - $event->getBot()->getStartTime();
		$repliesAmount = $event->getBot()->getRepliesCounter();

		$timeUnits = array ('dia' => 86400, 'hora' => 3600, 'minuto' => 60, 'segundo' => 1);

		$tmp = $uptime;
		$msg = "";
		foreach ($timeUnits as $name => $seconds) {
			$calc = floor($tmp / $seconds);
			$tmp -= $calc * $seconds;

			if ($calc > 0) {
				$msg .= "$calc $name";
				if ($calc > 1) {
					$msg .= "s";
				}
				$msg .= " e ";
			}
		}

		$reply = "Estou vivo ha $msg respondi a $replies mensagens ate agora.";

		if ($event->isPrivate()) {
			$destination = $sender;
		} else {
			$reply = "$sender: $reply";
			$destination = $event->getDestination();
		}

		return new BotMessage($destination, $reply);
	}
}
?>