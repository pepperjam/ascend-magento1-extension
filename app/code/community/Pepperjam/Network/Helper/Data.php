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

class Pepperjam_Network_Helper_Data extends Mage_Core_Helper_Abstract
{
	/** value for the source cookie */
	const SOURCE_KEY_VALUE_EBAY = 'eean';
	const SOURCE_KEY_VALUE_PEPPERJAM = 'pepperjam';
	/** prefix added to the source key name set in the admin panel to create a unique cookie name */
	const SOURCE_COOKIE_PREFIX = 'pepperjam_network_';

	/**
	 * Build the beacon url given an array keys
	 * @param array $params
	 * @return string
	 */
	public function buildBeaconUrl(array $params)
	{
		return Mage::helper('pepperjam_network/config')->getBeaconBaseUrl() . '?' .
			http_build_query($params);
	}

	/**
	 * Get all unique configured program ids. Program ids may only be set at the
	 * website level, so only get the program id for the default store for
	 * each website.
	 * @return array
	 */
	public function getAllProgramIds()
	{
		$config = Mage::helper('pepperjam_network/config');
		return array_unique(array_filter(array_map(
			function ($website) use ($config) {
				return $config->getProgramId($website->getDefaultStore());
			},
			Mage::app()->getWebsites()
		)));
	}

	/**
	 * Get a single store view for a program id. As program ids are configured
	 * only at the global or website level, the store view selecetd will be
	 * the default store view for the scope the configuration is set at. When
	 * set globally, the default store view for the Magento instance will be
	 * selected. When set at a website level, the default store view for that
	 * website will be used.
	 * @param  string $programId
	 * @return Mage_Core_Model_Store|null
	 */
	public function getStoreForProgramId($programId)
	{
		$config = Mage::helper('pepperjam_network/config');
		// Check for the default store view to be this program id first, will match
		// when the program id is set at the global level.
		$defaultStoreView = Mage::app()->getDefaultStoreView();
		$defaultProgramId = $config->getProgramId($defaultStoreView);
		if ($programId === $defaultProgramId) {
			return $defaultStoreView;
		}
		// When set at the website level, use the first website encountered
		// with a matching program id
		foreach (Mage::app()->getWebsites() as $website) {
			$storeView = $website->getDefaultStore();
			if ($config->getProgramId($storeView) === $programId) {
				return $storeView;
			}
		}
		return null;
	}

	/**
	 * Get all store views that have a program id that matches the given
	 * program id
	 * @param  string $programId
	 * @return Mage_Core_Model_Store[]
	 */
	public function getAllStoresForProgramId($programId)
	{
		$config = Mage::helper('pepperjam_network/config');
		return array_filter(
			Mage::app()->getStores(),
			function ($store) use ($config, $programId) {
				return $config->getProgramId($store) === $programId;
			}
		);
	}

	/**
	 * take a boolean value and return the string 'yes' or 'no' when the boolean
	 * value is true or false
	 * @param bool $value
	 * @return string
	 * @codeCoverageIgnore
	 */
	public function parseBoolToYesNo($value)
	{
		return $value?'yes':'no';
	}

	/**
	 * helper function to take the source key name set in the admin panel
	 * and prepend a string to create a unique name for the cookie
	 *
	 * @return string
	 */
	public function getSourceCookieName()
	{
		$key = Mage::helper('pepperjam_network/config')->getSourceKeyName();

		return self::SOURCE_COOKIE_PREFIX.$key;
	}

	/**
	 * True if the cookie exists and has a value of SOURCE_KEY_VALUE
	 * False otherwise
	 *
	 * @return bool
	 */
	public function isValidCookie()
	{
		$cookie = $this->getSourceCookieName();
		$value = Mage::getModel('core/cookie')->get($cookie);
		return in_array($value, array(self::SOURCE_KEY_VALUE_PEPPERJAM, self::SOURCE_KEY_VALUE_EBAY));
	}

	/**
	 * Check to see if any orders have been made by the customer before
	 * @param  Mage_Sales_Model_Order $order
	 * @return boolean This is the first order by this customer (email address)
	 */
	public function isNewToFile(Mage_Sales_Model_Order $order)
	{
		// Customers are being identified by emails
		$customerEmail = $order->getCustomerEmail();

		// Look up any orders that use the same email
		$orderCollection = Mage::getModel('sales/order')->getCollection();
		$orderCollection->addFieldToFilter('customer_email', $customerEmail);

		// Current order should be only order if new
		return $orderCollection->count() <= 1;
	}

	/**
	 * Get Commissioning Category assigned to the item or pick one of the assigned categories if one isn't set
	 * @param  Mage_Sales_Model_Order_Item $item Order Item
	 * @return int Category ID
	 */
	public function getCommissioningCategory(Mage_Sales_Model_Order_Item $item)
	{
		$category = $item->getProduct()->getCommissioningCategory();
		if ($category == '' || $category == null) {

			$categoryIds = $item->getProduct()->getCategoryIds();
			// if there are any categories, grab the first
			if (count($categoryIds))
				$category = $categoryIds[0];
			else
				$category = 0;
		}

		return $category;
	}
}
