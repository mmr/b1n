<?
/**
 * Magic 8 Ball Operation.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: Magic8Operation.php,v 1.1.1.1 2006/02/05 22:42:08 mmr Exp $
 */
final class Magic8Operation extends BotOperation {
	/**
	 * Possible answers.
	 * TODO (mmr) isolate this in a config file which can be dynamically loaded!
	 */
	private $responses = array (
		'Sim.',
		'Hmm, sim...',
		'Definitivamente!',
		'Com certeza!',
		'Yep!',
		'Acredito que sim.',
		'Claro!',

		'Talvez.',
		'Provavelmente sim...',
		'Provavelmente nao...',
		'Eh possivel...',
		'Se eu te disser vou ter de te matar!',
		'Nao faca nada que eu nao faria.',

		'Nao.',
		'Hmm, nao...',
		'Definitivamente nao!',
		'Sem chance.',
		'Nope!',
		'Acredito que nao.',
		'De jeito nenhum!');

	/**
	 * Reply to the operation.
	 * @param $event event that triggered the operation.
	 * @return BotMessage with magic 8's answer.
	 */
	public function reply(BotEvent $event) {
		$sender = $event->getSender();
		$message = $event->getMessage();

		if (preg_match("/([^\s]+)\s+ou\s+([^\s?]+)/i", $message)) {
			$responses = spliti(" ou ", $message);
		} else {
			$responses = $this->responses;
		}
		$response = mt_rand(0, count($responses) - 1);
		$reply = $responses[$response];
		$reply = trim(str_replace("?", "", $reply));

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