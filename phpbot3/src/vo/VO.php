<?php
/**
 * Abstraction of Value Object, all VOs should extend it.
 * @author Marcio Ribeiro (mmr)
 * @version $Id: VO.php,v 1.2 2006/02/06 01:18:28 mmr Exp $
 */
abstract class VO {
	private $id = -1;
	private $insertDate = null;
	private $lastUpdateDate = null;

	/**
	 * Returns the id of this VO.
	 * @return the id of this VO.
	 */
	public function getId() {
		return $this->id;		
	}

	/**
	 * Sets the id of this VO.
	 * @param $id the id to set.
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * Returns the date of insertion of this VO.
	 * @return the date of insertion of this VO.
	 */
	public function getInsertDate() {
		return $this->insertDate;
	}

	/**
	 * Returns the date of the last update on this VO.
	 * @return the date of the last update on this VO.
	 */
	public function getLastUpdateDate() {
		return $this->lastUpdateDate;
	}

	/**
	 * Checks if two VOs are equals, based in the ID.
	 * @param $vo the VO to compare.
	 * @return <code>true</code> if they are equal, <code>false</code> otherwise.
	 */
	public function equals(VO $vo) {
		return $this->getId() == $vo->getId();
	}
}
?>
