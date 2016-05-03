<?php
/**
 * Copyright (c) 2016 Pepperjam Network.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Pepperjam Network
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://assets.pepperjam.com/legal/magento-connect-extension-eula.pdf
 *
 * @copyright   Copyright (c) 2016 Pepperjam Network. (http://www.pepperjam.com/)
 * @license     http://assets.pepperjam.com/legal/magento-connect-extension-eula.pdf  Pepperjam Network Magento Extensions End User License Agreement
 */

abstract class Pepperjam_Network_Model_Feed_Order_Abstract extends Pepperjam_Network_Model_Feed_Abstract
{
	// Time format to use for the SQL statements
	const SELECT_TIME_FORMAT = 'Y-m-d H:i:s';
	const FILENAME_TIME_FORMAT = 'YmdHis';
	/**
	 * Time stamp considered to be the time at which the feed runs - used for
	 * generating the file name and the cutoff time for capturing order changes.
	 * @var int
	 */
	protected $_startTime;
	/**
	 * Allow for the "start" time of the feed to be passed in the constructor
	 * $args array. If given, this should be the time at which the feed is run.
	 * @param array $args
	 */
	public function __construct($args = array())
	{
		parent::__construct($args);
		$this->_startTime = isset($args['start_time']) ? $args['start_time'] : time();
	}
	/**
	 * Get the ids of all stores that should be included in the feed. Only orders
	 * placed for these stores will be included in the corrected feed.
	 * @return array
	 */
	protected function _getStoreIdsToInclude()
	{
		return array_map(
			function ($store) {
				return $store->getId();
			},
			Mage::helper('pepperjam_network')->getAllStoresForProgramId(
				Mage::helper('pepperjam_network/config')->getProgramId($this->_store)
			)
		);
	}
	/**
	 * @see parent::_getFileName
	 */
	protected function _getFileName()
	{
		return sprintf(
			$this->_getFileNameFormat(),
			Mage::helper('pepperjam_network/config')->getProgramId($this->getStore()),
			date(static::FILENAME_TIME_FORMAT, $this->_startTime)
		);
	}
	/**
	 * Get the format string used to build the feed file name.
	 * @return string
	 */
	abstract protected function _getFileNameFormat();
}
