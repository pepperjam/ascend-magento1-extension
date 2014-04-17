<?php

class EbayEnterprise_Affiliate_Model_Feed_Order_Itemized
	extends EbayEnterprise_Affiliate_Model_Feed_Abstract
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
	 * @see parent::_getItems
	 */
	protected function _getItems()
	{
		$lastRunTime = date(static::SELECT_TIME_FORMAT, Mage::helper('eems_affiliate/config')->getOrderLastRunTime() ?: 0);
		$startTime = date(static::SELECT_TIME_FORMAT, $this->_startTime);
		$storeIds = array_map(
			function ($store) { return $store->getId(); },
			Mage::helper('eems_affiliate')->getAllStoresForProgramId(
				Mage::helper('eems_affiliate/config')->getProgramId($this->_store)
			)
		);
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
				'main_table.store_id IN (?)', $storeIds
			)
			->where(
				"(o.original_increment_id IS NOT NULL AND o.created_at >= '$lastRunTime' AND o.created_at < '$startTime') OR" .
				"(cmo.created_at IS NOT NULL AND cmo.created_at >= '$lastRunTime' AND cmo.created_at < '$startTime') OR" .
				"(o.state = 'canceled' AND o.updated_at >= '$lastRunTime' AND o.updated_at < '$startTime' AND o.relation_child_id IS NULL)"
			);

		return $collection;
	}
	/**
	 * @see parent::_getFeedFields
	 */
	protected function _getFeedFields()
	{
		return explode(',', Mage::helper('eems_affiliate/config')->getItemizedOrderFeedFields());
	}
	/**
	 * @see parent::_getFileName
	 */
	protected function _getFileName()
	{
		$config = Mage::helper('eems_affiliate/config');
		return sprintf(
			$config->getItemizedOrderFeedFileFormat(),
			$config->getProgramId($this->getStore()),
			date(static::FILENAME_TIME_FORMAT, $this->_startTime)
		);
	}
	/**
	 * Allow for the "start" time of the feed to be passed in the constructor
	 * $args array. If given, this should be the time at which the feed is run.
	 * @param array $args
	 */
	public function __construct($args=array())
	{
		parent::__construct($args);
		$this->_startTime = isset($args['start_time']) ? $args['start_time'] : time();
	}
}
