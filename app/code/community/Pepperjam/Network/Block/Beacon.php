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

class Pepperjam_Network_Block_Beacon extends Mage_Core_Block_Template
{
	/**
	 * The 'PID' beacon URL querystring key
	 */
	const KEY_PID = 'PID';

	/**
	 * The 'OID' beacon URL querystring key
	 */
	const KEY_OID = 'OID';

	/**
	 * The 'AMOUNT' beacon URL querystring key
	 */
	const KEY_AMOUNT = 'AMOUNT';

	/**
	 * The 'TYPE' beacon URL querystring key
	 */
	const KEY_TYPE = 'TYPE';

	/**
	 * The 'QTY' beacon URL querystring key
	 */
	const KEY_QTY = 'QTY';

	/**
	 * The 'TOTALAMOUNT' beacon URL querystring key
	 */
	const KEY_TOTALAMOUNT = 'TOTALAMOUNT';

	/**
	 * The 'INT' beacon URL querystring key
	 */
	const KEY_INT = 'INT';

	/**
	 * The 'ITEM' beacon URL querystring key
	 */
	const KEY_ITEM = 'ITEM';

	/**
	 * The 'PROMOCODE' beacon URL querystring key
	 */
	const KEY_PROMOCODE = 'PROMOCODE';

	/**
	 * Dynamic query keys
	 */
	const KEY_DYNAMIC_PROGRAM_ID = 'PROGRAM_ID';
	const KEY_DYNAMIC_ORDER_ID = 'ORDER_ID';
	const KEY_DYNAMIC_ITEM_ID = 'ITEM_ID';
	const KEY_DYNAMIC_ITEM_PRICE = 'ITEM_PRICE';
	const KEY_DYNAMIC_QUANTITY = 'QUANTITY';
	const KEY_DYNAMIC_CATEGORY = 'CATEGORY';
	const KEY_DYNAMIC_NEW_TO_FILE = 'NEW_TO_FILE';
	const KEY_DYNAMIC_COUPON = 'COUPON';

	/**
	 * @var Mage_Sales_Model_Order
	 * @see self::_getOrder
	 */
	protected $_order;

	/** @var  Pepperjam_Network_Helper_Data */
	protected $_helper = null;

	/** @var  Pepperjam_Network_Helper_Config */
	protected $_configHelper = null;

	protected function _construct()
	{
		$helper = Mage::helper('pepperjam_network');

		if ($helper->isValidCookie()) {
			$helper = $this->_getHelper();
			$order = $this->_getOrder();

			$order->setNetworkSource($helper->getCookieValue($helper->getSourceCookieName()));
			$order->setNetworkClickId($helper->getCookieValue($helper->getClickCookieName()));
			$order->setNetworkPublisherId($helper->getCookieValue($helper->getPublisherCookieName()));

			$order->save();
		}
	}

	/**
	 * @return Pepperjam_Network_Helper_Data
	 */
	protected function _getHelper()
	{
		if (!$this->_helper) {
			$this->_helper = Mage::helper('pepperjam_network');
		}

		return $this->_helper;
	}

	/**
	 * @return Pepperjam_Network_Helper_Config
	 */
	protected function _getConfigHelper()
	{
		if (!$this->_configHelper) {
			$this->_configHelper = Mage::helper('pepperjam_network/config');
		}

		return $this->_configHelper;
	}

	/**
	 * Get the last order.
	 * @return Mage_Sales_Model_Order | null
	 */
	protected function _getOrder()
	{
		if (!($this->_order instanceof Mage_Sales_Model_Order)) {
			$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
			if ($orderId) {
				$this->_order = Mage::getModel('sales/order')->load($orderId);
			}
		}
		return $this->_order;
	}

	/**
	 * Get the beacon URL.
	 * @return string | null
	 */
	public function getBeaconUrl()
	{
		$order = $this->_getOrder();

		$url = null;

		if ($order instanceof Mage_Sales_Model_Order) {
			if (Mage::helper('pepperjam_network/config')->isItemizedOrders()) {
				$params = $this->_buildItemizedParams($order);
			} elseif (Mage::helper('pepperjam_network/config')->isDynamicOrders()) {
				$params = $this->_buildDynamicParams($order);
			} else {
				$params = $this->_buildBasicParams($order);
			}

			$url = Mage::helper('pepperjam_network')->buildBeaconUrl($params);
		}
		return $url;
	}

	/**
	 * build common params array
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildCommonParams(Mage_Sales_Model_Order $order)
	{
		$params = array(
			static::KEY_PID => Mage::helper('pepperjam_network/config')->getProgramId(),
			static::KEY_OID => $order->getIncrementId(),
		);
		$couponCode = trim($order->getCouponCode());
		return ($couponCode !== '')?
			array_merge($params, array(static::KEY_PROMOCODE => $couponCode)) : $params;
	}

	/**
	 * build basic params array for non itemized beacon URL
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildBasicParams(Mage_Sales_Model_Order $order)
	{
		$params = $this->_buildCommonParams($order);
		$params[static::KEY_AMOUNT] = number_format($order->getSubtotal() + $order->getDiscountAmount() + $order->getShippingDiscountAmount(), 2, '.', '');
		$params[static::KEY_TYPE] = Mage::helper('pepperjam_network/config')->getTransactionType();

		return $params;
	}

	/**
	 * build itemized order params array for itemized beacon URL
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildItemizedParams(Mage_Sales_Model_Order $order)
	{
		$params = $this->_buildCommonParams($order);
		$params[static::KEY_INT] = Mage::helper('pepperjam_network/config')->getInt();
		$increment = 1; // incrementer for the unique item keys
		foreach ($order->getAllItems() as $item) {
			// need to ignore the bundle parent as it will contain collected total
			// for children but not discounts
			$position = $this->_getDupePosition($params, $item);
			// ignore the parent configurable quantity - quantity of config products
			// will come from the simple used product with the same SKU
			$quantity = $item->getProductType() === Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE ?
				0 : (int) $item->getQtyOrdered();
			// consider parent bundle products to be 0.00 total (if the pricing is dynamic)
			// total of the bundleis the sum of all child products which are also included
			// in the beacon so including both totals would effectively double the price of
			// the bundle
			//
			// Divide discount amount by quantity to get per item discount
			$total = $item->getRowTotal() - $item->getDiscountAmount();
			if ($item->getProductType() === Mage_Catalog_Model_Product_Type::TYPE_BUNDLE && $item->getProduct()->getPriceType() == Mage_Bundle_Model_Product_Price::PRICE_TYPE_DYNAMIC) {
				$total = 0.00;
			}

			if ($position) {
				// we detected that the current item already exist in the params array
				// and have the key increment position let's simply adjust
				// the qty and total amount

				$params[static::KEY_QTY . $position] += $quantity;
				$params[static::KEY_AMOUNT . $position] += $total;
			} else {
				$params = array_merge($params, array(
					static::KEY_ITEM . $increment => $item->getSku(),
					static::KEY_QTY . $increment => $quantity,
					static::KEY_AMOUNT . $increment => $total,
				));
				$increment++; // only get incremented when a unique key have been appended
			}
		}

		// Calculate average cost
		for ($i = 1; $i < $increment; $i++) {
			$itemTotal = $params[static::KEY_AMOUNT . $i];
			$itemQuantity = $params[static::KEY_QTY . $i];
			$averageAmount = 0;
			if ($itemQuantity > 0 ) $averageAmount = $itemTotal/$itemQuantity;

			$params[static::KEY_AMOUNT . $i] = number_format($averageAmount, 2, '.', '');
		}


		return $params;
	}

	/**
	 * build dynamic order params array for dynamic beacon URL
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildDynamicParams(Mage_Sales_Model_Order $order)
	{
		$helper = Mage::helper('pepperjam_network');

		$params = $this->_buildItemizedParams($order);

		// Swap query key names for dynamic versions
		$params[self::KEY_DYNAMIC_PROGRAM_ID] = $params[self::KEY_PID];
		$params[self::KEY_DYNAMIC_ORDER_ID] = $params[self::KEY_OID];
		unset($params[self::KEY_PID]);
		unset($params[self::KEY_OID]);
		if (isset($params[self::KEY_PROMOCODE])) {
			$params[self::KEY_DYNAMIC_COUPON] = $params[self::KEY_PROMOCODE];
			unset($params[self::KEY_PROMOCODE]);
		}

		// See if email has any history
		$params[self::KEY_DYNAMIC_NEW_TO_FILE] = (int)$helper->isNewToFile($order);

		$productIds = array();
		foreach($order->getAllItems() as $item) {
			$productIds[] = $item->getProduct()->getId();
		}

		$productCollection = Mage::getModel('catalog/product')->getCollection()
			->addAttributeToFilter('entity_id', array('in', $productIds))
			->addCategoryIds();

		// No need for increment, all items are in param already
		$lastPosition = 0;
		foreach($order->getAllItems() as $item) {
			// Every item should be found here
			$position = $this->_getDupePosition($params, $item);

			// Add category IDs
			$product = $productCollection->getItemById($item->getProduct()->getId());
			$item->getProduct()->setCategoryIds($product->getCategoryIds());

			// Get item's category
			$params[self::KEY_DYNAMIC_CATEGORY . $position] = $helper->getCommissioningCategory($item);

			// Update last position
			$lastPosition = max($lastPosition, $position);
		}

		// Swap key names for dynamic versions
		for($position = 1; $position <= $lastPosition; $position += 1) {
			// Replace query string keys
			$params[self::KEY_DYNAMIC_ITEM_ID . $position] = $params[self::KEY_ITEM . $position];
			$params[self::KEY_DYNAMIC_ITEM_PRICE . $position] = $params[self::KEY_AMOUNT . $position];
			$params[self::KEY_DYNAMIC_QUANTITY . $position] = $params[self::KEY_QTY . $position];
			unset($params[self::KEY_ITEM . $position]);
			unset($params[self::KEY_AMOUNT . $position]);
			unset($params[self::KEY_QTY . $position]);
		}

		return $params;
	}

	/**
	 * check if the current sku already exists in the params data if so return
	 * the position it is found in
	 * @param array $params the given array of keys needed to build the beacon URL querystring
	 * @param Mage_Sales_Model_Order_Item $item
	 * @return int the item position where dupe found otherwise zero
	 */
	protected function _getDupePosition(array $params, Mage_Sales_Model_Order_Item $item)
	{
		$key = array_search($item->getSku(), $params, true);
		return ($key !== false)?
			(int) str_replace(static::KEY_ITEM, '', $key) : 0;
	}

	/**
	 * Whether or not to display the beacon.
	 *
	 * Show the pixel only if tracking is enabled and we have a valid order
	 * AND either conditional pixel logic is OFF or it is ON and we have
	 * a valid cookie.
	 *
	 * @return bool
	 */
	public function showBeacon()
	{
		$config = $this->_getConfigHelper();

		return (
			$config->trackByPixel() &&
			(
				$config->isEnabled() &&
				$this->_getOrder() instanceof Mage_Sales_Model_Order
			) &&
			(
				$this->_getHelper()->isValidCookie() ||
				!$config->isAttributionEnabled()
			)
		);
	}
}
