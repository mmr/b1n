<?
/**
 * PhpBot.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: PhpBot.php,v 1.4 2006/02/06 01:44:46 mmr Exp $
 */
require_once("vo/ServerVO.php");
require_once("vo/ChannelVO.php");
require_once("vo/UserVO.php");
require_once("dao/UserDAO.php");

class PhpBot {
    private $operations = null;
    private $operationsDir = null;

	public function __construct() {
        $this->loadOperations();

        $this->irc = Irc->getInstance();
        $this->irc->addEventListener($this);
        $this->irc->joinChannels();
	}

    private function loadOperations() {
        foreach ($this->getOperations() as $operation => $trigger) {
            $file = $this->getOperationsDir() . "/" . $operation . ".php";

            if (! is_readable($file)) {
                throw new Exception("Cannot load Operation $operation");
            }

            require_once($file);
            $this->operations[$operation] = new $operation($trigger);
        }
    }
}

$bot = new PhpBot();
?>
