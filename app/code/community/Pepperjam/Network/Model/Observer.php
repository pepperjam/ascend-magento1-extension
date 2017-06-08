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
class Pepperjam_Network_Model_Observer {
	const PRODUCT_LOG_MESSAGE = 'Generating Product feed: program id %s, default store view: %s';

	/**
	 * This observer method is the entry point to generating product feed when
	 * the CRONJOB 'pepperjam_network_generate_product_feed' run.
	 * @return void
	 */
	public function createProductFeed() {
		$config = Mage::helper('pepperjam_network/config');

		if (!$config->isProductFeedEnabled()) {
			Mage::log(Mage::helper('pepperjam_network')->__('Product feed disabled'), Zend_Log::NOTICE);
			return;
		}

		$helper = Mage::helper('pepperjam_network');
		foreach ($helper->getAllProgramIds() as $programId) {
			$store = $helper->getStoreForProgramId($programId);
			Mage::log(
				sprintf(static::PRODUCT_LOG_MESSAGE, $programId, $store->getName()),
				Zend_Log::INFO
			);

			Mage::getModel('pepperjam_network/feed_product', array(
				'store' => $store,
			))->generateFeed();
		}
	}

	/**
	 * Generate the order corrected feed.
	 * @return self
	 */
	public function createCorrectedOrdersFeed() {
		$config = Mage::helper('pepperjam_network/config');

		if (!$config->isAttributionEnabled() || !$config->isOrderCorrectionFeedEnabled()) {
			Mage::log(Mage::helper('pepperjam_network')->__('Corrected order feed disabled'), Zend_Log::NOTICE);
			return;
		}

		$startTime = time();

		$feedAlias = 'feed_order_' . $config->getOrderType();

		Mage::log(sprintf('[%s] Generating %s correction feed', __CLASS__, $feedAlias), Zend_Log::INFO);

		$helper = Mage::helper('pepperjam_network');
		foreach ($helper->getAllProgramIds() as $programId) {
			Mage::getModel(
				"pepperjam_network/{$feedAlias}",
				array('store' => $helper->getStoreForProgramId($programId), 'start_time' => $startTime)
			)->generateFeed('corrections');
		}

		Mage::helper('pepperjam_network/config')->updateOrderCorrectionLastRunTime($startTime);
		return $this;
	}

	/**
	 * Generate the order feed.
	 * @return self
	 */
	public function createOrdersFeed() {
		$config = Mage::helper('pepperjam_network/config');

		$startTime = time();

		$feedAlias = 'feed_order_' . $config->getOrderType();

		Mage::log("[" . __CLASS__ . "] Generating $feedAlias feed", Zend_Log::INFO);

		$helper = Mage::helper('pepperjam_network');
		foreach ($helper->getAllProgramIds() as $programId) {
			$feedModel = Mage::getModel(
				"pepperjam_network/$feedAlias",
				array('store' => $helper->getStoreForProgramId($programId), 'start_time' => $startTime)
			)->generateFeed('new');
		}

		$config->updateOrderLastRunTime($startTime);

		return $this;
	}

	/**
	 * Transfer attribution attributes to new order after edit
	 * @param  Varien_Event_Observer $observer
	 * @return self
	 */
	public function transferAttribution($observer) {
		$order = $observer->getEvent()->getData('order');
		if (!is_null($order->getRelationParentId())) {
			$parentOrder = Mage::getModel('sales/order')->load($order->getRelationParentId());

			$order->setNetworkSource($parentOrder->getNetworkSource());
			$order->setNetworkClickId($parentOrder->getNetworkClickId());
			$order->setNetworkPublisherId($parentOrder->getNetworkPublisherId());
		}

		return $this;
	}
}
