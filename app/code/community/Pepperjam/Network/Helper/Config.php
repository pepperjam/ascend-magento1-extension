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
	const PROGRAM_ID_PATH = 'pepperjam/pepperjam_network/program_id';
	const TRANSACTION_TYPE_PATH = 'pepperjam/pepperjam_network/transaction_type';
	const EXPORT_FILE_PATH_CONFIG_PATH = 'pepperjam/pepperjam_network/export_path';
	const CALLBACK_MAPPINGS_PATH = 'pepperjam/pepperjam_network/feeds/callback_mappings';
	const PRODUCT_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network_product_attribute_map';
	const PRODUCT_FEED_FILENAME_FORMAT_PATH = 'pepperjam/pepperjam_network/feeds/product/file_name_format';
	const DYNAMIC_ORDER_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_dynamic/fields';
	const ITEMIZED_ORDER_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_itemized/fields';
	const BASIC_ORDER_FEED_MAPPING_PATH = 'pepperjam/pepperjam_network/feeds/order_basic/fields';
	const DYNAMIC_ORDER_FEED_FILE_FORMAT_PATH = 'pepperjam/pepperjam_network/feeds/order_dynamic/file_name_format';
	const ITEMIZED_ORDER_FEED_FILE_FORMAT_PATH = 'pepperjam/pepperjam_network/feeds/order_itemized/file_name_format';
	const BASIC_ORDER_FEED_FILE_FORMAT_PATH = 'pepperjam/pepperjam_network/feeds/order_basic/file_name_format';
	const ORDER_LAST_RUN_PATH = 'pepperjam/pepperjam_network/feed/last_run_time';
	const JS_FILES = 'pepperjam/pepperjam_network/js_files';
	const CONDITIONAL_PIXEL_ENABLED = 'pepperjam/pepperjam_network/conditional_pixel_enabled';
	const SOURCE_KEY_NAME = 'pepperjam/pepperjam_network/source_key_name';
	const PRODUCT_FEED_ENABLED = 'pepperjam/pepperjam_network/product_feed_enabled';
	const ORDER_FEED_ENABLED = 'pepperjam/pepperjam_network/order_feed_enabled';

	const TRANSACTION_TYPE_SALE = '1';
	const TRANSACTION_TYPE_LEAD = '2';

	const ORDER_TYPE_BASIC = 'basic';
	const ORDER_TYPE_ITEMIZED = 'itemized';
	const ORDER_TYPE_DYNAMIC = 'dynamic';

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
		return $this->getOrderType() == static::ORDER_TYPE_DYNAMIC;
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
	 * Get the configured dynamic order feed file format
	 * @param  mixed $store
	 * @return string
	 */
	public function getDynamicOrderFeedFileFormat($store = null)
	{
		return Mage::getStoreConfig(static::DYNAMIC_ORDER_FEED_FILE_FORMAT_PATH, $store);
	}

	/**
	 * Get the configured itemized order feed file format
	 * @param  mixed $store
	 * @return string
	 */
	public function getItemizedOrderFeedFileFormat($store = null)
	{
		return Mage::getStoreConfig(static::ITEMIZED_ORDER_FEED_FILE_FORMAT_PATH, $store);
	}

	/**
	 * Get the configured basic order feed file format
	 * @param  mixed $store
	 * @return string
	 */
	public function getBasicOrderFeedFileFormat($store = null)
	{
		return Mage::getStoreConfig(static::BASIC_ORDER_FEED_FILE_FORMAT_PATH, $store);
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
	 * Get the last time the order corrections feed was run. Returns the string
	 * value saved in config. Always set globally so no need for a store context.
	 * @return string
	 */
	public function getOrderLastRunTime()
	{
		return Mage::getStoreConfig(self::ORDER_LAST_RUN_PATH);
	}

	/**
	 * Enable/disable conditional pixel logic
	 *
	 * @param null $store
	 * @return bool
	 */
	public function isConditionalPixelEnabled($store = null)
	{
		return Mage::getStoreConfig(self::CONDITIONAL_PIXEL_ENABLED, $store);
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
	 * Enable/disable order feed
	 *
	 * @param  null    $store
	 * @return boolean
	 */
	public function isOrderFeedEnabled($store = null)
	{
		return Mage::getStoreConfig(self::ORDER_FEED_ENABLED, $store);
	}
}
