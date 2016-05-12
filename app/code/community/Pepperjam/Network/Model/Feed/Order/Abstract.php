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
	protected function _getFileNameFormat()
	{
		if ($this->_feedType == self::ITEMS_NEW) {
			return Mage::helper('pepperjam_network/config')->getOrderFeedFileFormat();
		} else {
			return Mage::helper('pepperjam_network/config')->getOrderCorrectionFeedFileFormat();
		}
	}

	protected function _getBasicItems()
	{
		if ($this->_feedType == self::ITEMS_NEW) {
			$lastRunTime = date(static::SELECT_TIME_FORMAT, Mage::helper('pepperjam_network/config')->getOrderLastRunTime() ?: 0);
		} else {
			$lastRunTime = date(static::SELECT_TIME_FORMAT, Mage::helper('pepperjam_network/config')->getOrderCorrectionLastRunTime() ?: 0);
		}
		$lastRunTime = date(static::SELECT_TIME_FORMAT, Mage::helper('pepperjam_network/config')->getOrderLastRunTime() ?: 0);
		$startTime = date(static::SELECT_TIME_FORMAT, $this->_startTime);
		$storeIds = $this->_getStoreIdsToInclude();

		$collection = Mage::getResourceModel('sales/order_collection');
		$select = $collection->getSelect();
		$select
			->joinLeft(
				array('cmo' => $collection->getTable('sales/creditmemo')),
				'main_table.entity_id = cmo.order_id',
				array()
			)
			// this is far more pure SQL than should be here but I don't see a way to
			// get the proper groupings of where clauses without doing this
			->where(
				'main_table.store_id IN (?) AND main_table.network_source IS NOT NULL',
				$storeIds
			);

		if ($this->_feedType == self::ITEMS_NEW) {
			$config = Mage::helper('pepperjam_network/config');
			if ($config->trackByFeed()) {
				$select->where("main_table.state = 'complete'")
					->where("main_table.created_at >= :lastRunTime")
					->where("main_table.created_at < :startTime");
			} else {
				$select->where("relation_parent_id IS NOT NULL")
					->where("main_table.state = 'complete'")
					->where("main_table.created_at >= :lastRunTime")
					->where("main_table.created_at < :startTime");
			}
		} else {
			$select->where(
				"(cmo.created_at IS NOT NULL AND cmo.created_at >= :lastRunTime AND cmo.created_at < :startTime) OR" .
				"(main_table.state = 'canceled' AND main_table.updated_at >= :lastRunTime AND main_table.updated_at < :startTime)"
			);
		}

		$collection->addBindParam(':lastRunTime', $lastRunTime)
			->addBindParam(':startTime', $startTime);

		return $collection;
	}

	protected function _getItemizedItems()
	{
		if ($this->_feedType == self::ITEMS_NEW) {
			$lastRunTime = date(static::SELECT_TIME_FORMAT, Mage::helper('pepperjam_network/config')->getOrderLastRunTime() ?: 0);
		} else {
			$lastRunTime = date(static::SELECT_TIME_FORMAT, Mage::helper('pepperjam_network/config')->getOrderCorrectionLastRunTime() ?: 0);
		}
        $startTime = date(static::SELECT_TIME_FORMAT, $this->_startTime);
        $storeIds = $this->_getStoreIdsToInclude();

        $collection = Mage::getResourceModel('sales/order_item_collection');
        $select = $collection->getSelect();
        $select
            ->joinLeft(
                array('o' => $collection->getTable('sales/order')),
                'main_table.order_id = o.entity_id',
                array('o.increment_id', 'o.original_increment_id')
            )
            ->joinLeft(
                array('cmo' => $collection->getTable('sales/creditmemo')),
                'main_table.order_id = cmo.order_id',
                array()
            )
            // this is far more pure SQL than should be here but I don't see a way to
            // get the proper groupings of where clauses without doing this
            ->where(
                // get only items within the correct store scope and filter out any
                // configurable used simple products
                'main_table.store_id IN (?) AND o.network_source IS NOT NULL AND NOT (main_table.product_type="simple" AND main_table.parent_item_id IS NOT NULL AND main_table.row_total=0)',
                $storeIds
            )
            // The left joins can leave duplicate item rows
            // But the selected items will be identical, so we don't need them.
            ->distinct();

		if ($this->_feedType == self::ITEMS_NEW) {
			$config = Mage::helper('pepperjam_network/config');
			if ($config->trackByFeed()) {
				$select->where("o.state = 'complete'")
					->where("o.updated_at >= :lastRunTime")
					->where("o.updated_at < :startTime");
			} else {
				$select->where("o.relation_parent_id IS NOT NULL")
					->where("o.state = 'complete'")
					->where("o.updated_at >= :lastRunTime")
					->where("o.updated_at < :startTime");
			}
		} else {
			$select->where(
                "(cmo.created_at IS NOT NULL AND cmo.created_at >= :lastRunTime AND cmo.created_at < :startTime) OR " .
                "(o.state = 'canceled' AND o.updated_at >= :lastRunTime AND o.updated_at < :startTime)"
			);
		}

        $collection->addBindParam(':lastRunTime', $lastRunTime)
            ->addBindParam(':startTime', $startTime);

        return $collection;
	}
}
