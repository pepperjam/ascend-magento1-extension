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

class Pepperjam_Network_Model_Feed_Product extends Pepperjam_Network_Model_Feed_Abstract
{
	const DELIMITER = "\t";
	const FIELD_PRODUCT_URL = 'product_url';
	/**
	 * @see parent::_getItems
	 */
	protected function _getItems()
	{
		$store = $this->getStore();
		$requiredMap = Mage::helper('pepperjam_network/config')->getRequiredProductFeedFields($store);

		$productCollection = Mage::getResourceModel('catalog/product_collection')
			->setStore($this->getStore())
			->addAttributeToSelect(array('*'))
			->addStoreFilter($this->getStore())
			->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);

		foreach ($requiredMap as $field) {
			if ($field != self::FIELD_PRODUCT_URL) {
				$productCollection->addFieldToFilter($field, array('notnull' => true));
			}
		}

		return $productCollection;
	}
	/**
	 * Get an array of callback mappings for the feed. Should result in an array
	 * with keys for the field in the CSV and a value of an array used to
	 * represent a mapping callback.
	 * @see parent::_invokeCallback
	 */
	protected function _getFeedFields()
	{
		$store = $this->getStore();
		$callbackMap = Mage::helper('pepperjam_network/config')->getCallbackMappings($store);
		return array_filter(array_map(
			function ($key) use ($callbackMap) {
				return isset($callbackMap[$key])? $callbackMap[$key]['column_name'] : null;
			},
			array_keys(Mage::helper('pepperjam_network/config')->getProductFeedFields($store))
		));
	}
	/**
	 * @see parent::_applyMapping
	 * @param  mixed $item Likely a Varien_Object but could really be anything.
	 * @return array
	 */
	protected function _applyMapping($item)
	{
		$fields = array();
		$helper = Mage::helper('pepperjam_network/config');
		$store = $this->getStore();
		$mappings = $helper->getCallbackMappings($store);
		$columns = $helper->getProductFeedFields($store);
		foreach ($this->_getFeedFields() as $feedField) {
			// If the mapping doesn't exist, supplying an empty array will eventually
			// result in an exception for being an invalid config mapping.
			// @see self::_validateCallbackConfig
			$callback = isset($mappings[$feedField]) ? $mappings[$feedField] : array();
			if ($columns[$feedField]) {
				$callback['params']['key'] = $columns[$feedField];
			}
			// exclude any mappings that have a type of "disabled"
			if (!isset($callback['type']) || $callback['type'] !== 'disabled') {
				$fields[] = $this->_invokeCallback($callback, $item);
			}
		}
		return $fields;
	}
	/**
	 * @see parent::_getFileName
	 */
	protected function _getFileName()
	{
		$config = Mage::helper('pepperjam_network/config');
		$store = $this->getStore();
		return sprintf(
			$config->getProductFeedFilenameFormat($store),
			$config->getProgramId($this->getStore($store))
		);
	}
	/**
	 * @see Pepperjam_Network_Model_Feed_Abstract::_getDelimiter
	 * @return string
	 * @codeCoverageIgnore
	 */
	protected function _getDelimiter()
	{
		return static::DELIMITER;
	}
}
