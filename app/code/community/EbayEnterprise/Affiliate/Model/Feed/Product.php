<?php

class EbayEnterprise_Affiliate_Model_Feed_Product
	extends EbayEnterprise_Affiliate_Model_Feed_Abstract
{
	/**
	 * @see parent::_getItems
	 */
	protected function _getItems()
	{
		return Mage::getResourceModel('catalog/product_collection')
			->addAttributeToSelect(array('*'))
			->addStoreFilter($this->getStore());
	}
	/**
	 * Get an array of callback mappings for the feed. Should result in an array
	 * with keys for the field in the CSV and a value of an array used to
	 * represent a mapping callback.
	 * @see parent::_invokeCallback
	 */
	protected function _getFeedFields()
	{
		return explode(',', Mage::helper('eems_affiliate/config')->getProductFeedFields());
	}
	/**
	 * @see parent::_getFileName
	 */
	protected function _getFileName()
	{
		$config = Mage::helper('eems_affiliate/config');
		return sprintf(
			$config->getProductFeedFileFormat(),
			$config->getProgramId($this->getStore())
		);
	}
}