<?php
/**
 * Socket Client Layer.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: Socket.php,v 1.2 2006/02/06 01:44:47 mmr Exp $
 */
class Socket {
	private $socket = null;
	private $bytesSent = 0;
	private $bytesReceived = 0;
	const BUFSIZ = 512;

	public function __construct($servers) {
		$this->connect($servers);
	}

	public function __destruct() {
		$this->disconnect();
	}

	private function connect($servers) {
		if ($this->isConnected()) { 
			throw new Exception('Already connected');
		}

		if (! is_array($servers) || count($servers) == 0) {
			throw new Exception('No servers');
		}

		foreach($servers as $s) {
			if (! ereg("[^:]+:[0-9]+", $s)) {
				throw new Exception($s . ' is not a valid server');
			}
		}

		srand((float) microtime() * 1000000);
		shuffle($servers);

		foreach($servers as $s) {
			list($server, $port) = explode(":", $s);
			$server = gethostbyname($server);
			$this->socket = fsockopen($server, $port, $errno, $err);
			if ($this->isConnected()) { 
				break;
			}
		}

		if ($this->isConnected()) { 
			return true;
		} else {
			throw new Exception($errno . ' - ' . $err);
		}
	}

	private function isConnected() {
		return $this->socket;
	}

	public function write($buf) {
		$len = strlen($buf);
		$this->bytes_sent += $len;
		if ($len>0) {
			echo date('d/m/Y h:i:s') . " WRITE ($len): $buf";
		}
		return fputs($this->socket, $buf, $len);
	}

	public function read() {
		$buf = fgets($this->socket, self::BUFSIZ);
		$len = strlen($buf);
		$this->bytes_received += $len;
		if ($len>0) {
			echo date('d/m/Y h:i:s') . " READ ($len): $buf";
		}
		return $buf;
	}

	public function getBytesSent() {
		return $this->bytesSent;
	}

	public function getBytesReceived() {
		return $this->bytesReceived;
	}

	public function disconnect() {
		if ($this->isConnected()) {
			fclose($this->socket);
			$this->socket = NULL;
			return true;
		}
		return false;
	}
}
?>
