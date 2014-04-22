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
		$helper = Mage::helper('eems_affiliate');
		foreach ($helper->getAllProgramIds() as $programId) {
			$store = $helper->getStoreForProgramId($programId);
			Mage::log(
				sprintf(static::PRODUCT_LOG_MESSAGE, $programId, $store->getName()),
				Zend_Log::INFO
			);

			Mage::getModel('eems_affiliate/feed_product', array(
				'store' => $store
			))->generateFeed();
		}
	}
	/**
	 * Generate the order corrected feed.
	 * @return self
	 */
	public function createCorrectedOrdersFeed()
	{
		$startTime = time();

		$feedAlias = Mage::helper('eems_affiliate/config')->isItemizedOrders() ?
			'feed_order_itemized' : 'feed_order_basic';

		Mage::log(sprintf('[%s] Generating %s feed', __CLASS__, $feedAlias));

		$helper = Mage::helper('eems_affiliate');
		foreach ($helper->getAllProgramIds() as $programId) {
			Mage::getModel(
				"eems_affiliate/{$feedAlias}",
				array('store' => $helper->getStoreForProgramId($programId), 'start_time' => $startTime)
			)->generateFeed();
		}

		Mage::helper('eems_affiliate/config')->updateOrderLastRunTime($startTime);

		return $this;
	}
}
