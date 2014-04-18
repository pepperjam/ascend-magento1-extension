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
			Mage::log(sprintf('[%s] program id : %s', __METHOD__, $programId));
			$store = $helper->getStoreForProgramId($programId);

			if (!is_null($store)) {
				Mage::log(
					sprintf(static::PRODUCT_LOG_MESSAGE, $programId, $store->getName()),
					Zend_Log::INFO
				);

				Mage::getModel('eems_affiliate/feed_product', array(
					'store' => $store
				))->generateFeed();
			}
		}
	}
}