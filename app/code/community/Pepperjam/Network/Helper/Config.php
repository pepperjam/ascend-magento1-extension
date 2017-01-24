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
 *
 */

/**
 * @codeCoverageIgnore
 */
class Pepperjam_Network_Helper_Config
{
	const BEACON_URL_PATH = 'pepperjam/pepperjam_network/beacon_url';
	const ENABLED_PATH = 'pepperjam/pepperjam_network/active';
	const ORDER_TYPE_PATH = 'pepperjam/pepperjam_network/order_type';
	const TRACKING_METHOD_PATH = 'pepperjam/pepperjam_network/tracking_method';
	const PROGRAM_ID_PATH = 'pepperjam/pepperjam_network/program_id';
	const TRANSACTION_TYPE_PATH = 'pepperjam/pepperjam_network/transaction_type';
	const EXPORT_FILE_PATH_CONFIG_PATH = 'pepperjam/pepperjam_network/export_path';
	const CALLBACK_MAPPINGS_PATH = 'pepperjam/pepperjam_network/feeds/callback_mappings';
	const PRODUCT_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network_product_attribute_map';
	const PRODUCT_FEED_FILENAME_FORMAT_PATH = 'pepperjam/pepperjam_network/feeds/product/file_name_format';
	const DYNAMIC_ORDER_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_dynamic/fields';
	const ITEMIZED_ORDER_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_itemized/fields';
	const BASIC_ORDER_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_basic/fields';
	const DYNAMIC_ORDER_CORRECTION_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_correction_dynamic/fields';
	const ITEMIZED_ORDER_CORRECTION_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_correction_itemized/fields';
	const BASIC_ORDER_CORRECTION_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_correction_basic/fields';
	const ORDER_FEED_FILE_FORMAT_PATH = 'pepperjam/pepperjam_network/feeds/order_feed/file_name_format';
	const ORDER_CORRECTION_FEED_FILE_FORMAT_PATH = 'pepperjam/pepperjam_network/feeds/order_correction_feed/file_name_format';
	const ORDER_LAST_RUN_PATH = 'pepperjam/pepperjam_network/order_feed/last_run_time';
	const ORDER_CORRECTION_LAST_RUN_PATH = 'pepperjam/pepperjam_network/order_correction_feed/last_run_time';
	const JS_FILES = 'pepperjam/pepperjam_network/js_files';
	const ATTRIBUTION_ENABLED = 'pepperjam/pepperjam_network/attribution_enabled';
	const SOURCE_KEY_NAME = 'pepperjam/pepperjam_network/source_key_name';
	const CLICK_KEY_NAME = 'pepperjam/pepperjam_network/click_id_key_name';
	const PUBLISHER_KEY_NAME = 'pepperjam/pepperjam_network/publisher_id_key_name';
	const PRODUCT_FEED_ENABLED = 'pepperjam/pepperjam_network/product_feed_enabled';
	const ORDER_CORRECTION_FEED_ENABLED = 'pepperjam/pepperjam_network/order_correction_feed_enabled';

	const TRANSACTION_TYPE_SALE = '1';
	const TRANSACTION_TYPE_LEAD = '2';

	const ORDER_TYPE_BASIC = 'basic';
	const ORDER_TYPE_ITEMIZED = 'itemized';
	const ORDER_TYPE_DYNAMIC = 'dynamic';

	const TRACKING_METHOD_PIXEL = 'pixel';
	const TRACKING_METHOD_FEED = 'feed';

	/**
	 * retrieve the program id from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getProgramId($store = null)
	{
		return Mage::getStoreConfig(static::PROGRAM_ID_PATH, $store);
	}

	/**
	 * retrieve the transaction type from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getTransactionType($store = null)
	{
		return Mage::getStoreConfig(static::TRANSACTION_TYPE_PATH, $store);
	}

	/**
	 * retrieve the order type from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getOrderType($store = null)
	{
		return Mage::getStoreConfig(static::ORDER_TYPE_PATH, $store);
	}

	/**
	 * determine if orders should be itemized
	 * @param mixed $store
	 * @return bool
	 */
	public function isItemizedOrders($store = null)
	{
		return $this->getOrderType() == static::ORDER_TYPE_ITEMIZED;
	}

	/**
	 * determine if orders should be dynamic
	 * @param mixed $store
	 * @return bool
	 */
	public function isDynamicOrders($store = null)
	{
		return $this->getOrderType($store) == static::ORDER_TYPE_DYNAMIC;
	}

	/**
	 * retrieve tracking method
	 * @param  mixed  $store
	 * @return string
	 */
	public function getTrackingMethod($store = null)
	{
		return Mage::getStoreConfig(self::TRACKING_METHOD_PATH, $store);
	}

	/**
	 * should tracking be done via pixel?
	 * @param  mixed $store
	 * @return bool
	 */
	public function trackByPixel($store = null)
	{
		return $this->getTrackingMethod($store) == self::TRACKING_METHOD_PIXEL;
	}

	/**
	 * should tracking be done via feed?
	 * @param  mixed $store
	 * @return bool
	 */
	public function trackByFeed($store = null)
	{
		return $this->getTrackingMethod($store) == self::TRACKING_METHOD_FEED;
	}

	/**
	 * check if beacon pixel is enable in the store config
	 * @param mixed $store
	 * @return bool
	 */
	public function isEnabled($store = null)
	{
		return Mage::getStoreConfigFlag(static::ENABLED_PATH, $store);
	}

	/**
	 * retrieve the int from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getInt($store = null)
	{
		return strtoupper(self::getOrderType());
	}

	/**
	 * retrieve the base url of the beacon from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getBeaconBaseUrl($store = null)
	{
		return Mage::getStoreConfig(static::BEACON_URL_PATH, $store);
	}

	/**
	 * Get the configured export file path.
	 * @param  mixed $store
	 * @return string
	 */
	public function getExportFilePath($store = null)
	{
		return Mage::getStoreConfig(static::EXPORT_FILE_PATH_CONFIG_PATH, $store);
	}

	/**
	 * Get the callback mappings from the config
	 * @param  mixed $store
	 * @return array
	 */
	public function getCallbackMappings($store = null)
	{
		return Mage::getStoreConfig(static::CALLBACK_MAPPINGS_PATH, $store);
	}

	/**
	 * Get the configured feed mapping for the product feed.
	 * @param  mixed $store
	 * @return array
	 */
	public function getProductFeedFields($store = null)
	{
		return array_filter(Mage::getStoreConfig(static::PRODUCT_FEED_MAPPING_PATH, $store));
	}

	/**
	 * Get the fields that are required for the product feed
	 * @param  mixed
	 * @return array
	 */
	public function getRequiredProductFeedFields($store = null)
	{
		// Following the same method magento uses to render the config page
		$config = Mage::getSingleton('adminhtml/config');
		$pepperjamConfig = $config->getSection('pepperjam', '', '');
		$pepperjamConfig = $pepperjamConfig->groups->pepperjam_network_product_attribute_map->fields;
		$pepperjamFields = $pepperjamConfig->asArray();
		$requiredFields = array_keys(array_filter($pepperjamFields, function ($value) {
			return isset($value['validate']) && in_array('required-entry', explode(' ', $value['validate']));
		}));

		$allMappedFields = $this->getProductFeedFields($store);
		$requiredMappedFields = array_intersect_key($allMappedFields, array_flip($requiredFields));

		return $requiredMappedFields;
	}

	/**
	 * Get the configured product feed file name format
	 * @param  mixed $store
	 * @return string
	 */
	public function getProductFeedFilenameFormat($store = null)
	{
		return Mage::getStoreConfig(static::PRODUCT_FEED_FILENAME_FORMAT_PATH, $store);
	}

	/**
	 * Get the configured feed mapping for the dynamic orders feed.
	 * @param  mixed $store
	 * @return array
	 */
	public function getDynamicOrderFeedFields($store = null)
	{
		return Mage::getStoreConfig(static::DYNAMIC_ORDER_FEED_MAPPING_PATH, $store);
	}

	/**
	 * Get the configured feed mapping for the itemized orders feed.
	 * @param  mixed $store
	 * @return array
	 */
	public function getItemizedOrderFeedFields($store = null)
	{
		return Mage::getStoreConfig(static::ITEMIZED_ORDER_FEED_MAPPING_PATH, $store);
	}

	/**
	 * Get the configured feed mapping for the basic orders feed.
	 * @param  mixed $store
	 * @return array
	 */
	public function getBasicOrderFeedFields($store = null)
	{
		return Mage::getStoreConfig(static::BASIC_ORDER_FEED_MAPPING_PATH, $store);
	}

	/**
	 * Get the configured feed mapping for the dynamic order correction feed.
	 * @param  mixed $store
	 * @return array
	 */
	public function getDynamicOrderCorrectionFeedFields($store = null)
	{
		return Mage::getStoreConfig(static::DYNAMIC_ORDER_CORRECTION_FEED_MAPPING_PATH, $store);
	}

	/**
	 * Get the configured feed mapping for the itemized order correction feed.
	 * @param  mixed $store
	 * @return array
	 */
	public function getItemizedOrderCorrectionFeedFields($store = null)
	{
		return Mage::getStoreConfig(static::ITEMIZED_ORDER_CORRECTION_FEED_MAPPING_PATH, $store);
	}

	/**
	 * Get the configured feed mapping for the basic order correction feed.
	 * @param  mixed $store
	 * @return array
	 */
	public function getBasicOrderCorrectionFeedFields($store = null)
	{
		return Mage::getStoreConfig(static::BASIC_ORDER_CORRECTION_FEED_MAPPING_PATH, $store);
	}

	/**
	 * Get the configured order feed file format
	 * @param  mixed $store
	 * @return string
	 */
	public function getOrderFeedFileFormat($store = null)
	{
		return Mage::getStoreConfig(static::ORDER_FEED_FILE_FORMAT_PATH, $store);
	}

	/**
	 * Get the configured order correction feed file format
	 * @param  mixed $store
	 * @return string
	 */
	public function getOrderCorrectionFeedFileFormat($store = null)
	{
		return Mage::getStoreConfig(static::ORDER_CORRECTION_FEED_FILE_FORMAT_PATH, $store);
	}

	/**
	 * Update the last run time of the order create feed to the specified time,
	 * or the current time it no time is given. Always set globally so no need to
	 * ever be given a store context.
	 * @param  string $time
	 * @return self
	 */
	public function updateOrderLastRunTime($time = null)
	{
		Mage::getConfig()->saveConfig(self::ORDER_LAST_RUN_PATH, $time ?: time());
		Mage::app()->getStore()->resetConfig();
		return $this;
	}

	/**
	 * Update the last run time of the order create feed to the specified time,
	 * or the current time it no time is given. Always set globally so no need to
	 * ever be given a store context.
	 * @param  string $time
	 * @return self
	 */
	public function updateOrderCorrectionLastRunTime($time = null)
	{
		Mage::getConfig()->saveConfig(self::ORDER_CORRECTION_LAST_RUN_PATH, $time ?: time());
		Mage::app()->getStore()->resetConfig();
		return $this;
	}

	/**
	 * Get the last time the order corrections feed was run. Returns the string
	 * value saved in config. Always set globally so no need for a store context.
	 * @return string
	 */
	public function getOrderLastRunTime()
	{
		return Mage::getStoreConfig(self::ORDER_LAST_RUN_PATH);
	}

	/**
	 * Get the last time the order corrections feed was run. Returns the string
	 * value saved in config. Always set globally so no need for a store context.
	 * @return string
	 */
	public function getOrderCorrectionLastRunTime()
	{
		return Mage::getStoreConfig(self::ORDER_CORRECTION_LAST_RUN_PATH);
	}

	/**
	 * Enable/disable conditional pixel logic
	 *
	 * @param null $store
	 * @return bool
	 */
	public function isAttributionEnabled($store = null)
	{
		return Mage::getStoreConfig(self::ATTRIBUTION_ENABLED, $store);
	}

	/**
	 * Name of the affiliate source
	 *
	 * If conditional pixel logic is enabled then only display the pixel
	 * if the query string contains a key with this name
	 *
	 * @param null $store
	 * @return string
	 */
	public function getSourceKeyName($store = null)
	{
		return Mage::getStoreConfig(self::SOURCE_KEY_NAME, $store);
	}

	/**
	 * ID of the affiliate click
	 *
	 * If attribution logic is enabled, include the click id in the pixel
	 *
	 * @param null $store
	 * @return string
	 */
	public function getClickKeyName($store = null)
	{
		return Mage::getStoreConfig(self::CLICK_KEY_NAME, $store);
	}

	/**
	 * ID of the affiliate publisher
	 *
	 * If attribution logic is enabled, include the publisher id in the pixel
	 *
	 * @param null $store
	 * @return string
	 */
	public function getPublisherKeyName($store = null)
	{
		return Mage::getStoreConfig(self::PUBLISHER_KEY_NAME, $store);
	}

	/**
	 * Enable/disable product feed
	 *
	 * @param  null    $store
	 * @return boolean
	 */
	public function isProductFeedEnabled($store = null)
	{
		return Mage::getStoreConfig(self::PRODUCT_FEED_ENABLED, $store);
	}

	/**
	 * Enable/disable order Correction feed
	 *
	 * @param  null    $store
	 * @return boolean
	 */
	public function isOrderCorrectionFeedEnabled($store = null)
	{
		return Mage::getStoreConfig(self::ORDER_CORRECTION_FEED_ENABLED, $store);
	}
}
