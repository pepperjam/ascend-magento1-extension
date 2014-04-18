<?php

class EbayEnterprise_Affiliate_Model_Observer
{
	const PRODUCT_LOG_MESSAGE = 'Generating Product feed: program id %s, default store view: %s';
	/**
	 * This observer method is the entry point to generating product feed when
	 * the CRONJOB 'eems_affiliate_generate_product_feed' run.
	 * @return void
	 */
	public function createProductFeed()
	{
		$stores = Mage::helper('eems_affiliate')->getWebsitesDefaultStoreviews();
		foreach ($stores as $key => $store) {
			Mage::log(
				sprintf(static::PRODUCT_LOG_MESSAGE, $key, $store->getName()),
				Zend_Log::INFO
			);

			Mage::getModel('eems_affiliate/feed_product', array(
				'store' => $store
			))->generateFeed();
		}
	}
}