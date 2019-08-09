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
	 * The 'CLICK_ID' beacon URL querystring key
	 */
	const KEY_CLICKID = 'CLICK_ID';

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
	protected $_orders;

	/** @var  Pepperjam_Network_Helper_Data */
	protected $_helper = null;

	/** @var  Pepperjam_Network_Helper_Config */
	protected $_configHelper = null;

	protected function _construct()
	{
		$helper = Mage::helper('pepperjam_network');

		if ($helper->isValidCookie()) {
			$helper = $this->_getHelper();
			$orders = $this->_getOrders();

			foreach($orders as $order) {
				$order->setNetworkSource($helper->getCookieValue($helper->getSourceCookieName()));
				$order->setNetworkClickId($helper->getClickIds());
				$order->setNetworkPublisherId($helper->getCookieValue($helper->getPublisherCookieName()));

				$order->save();
			}
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
	protected function _getOrders()
	{
		if (!($this->_orders instanceof Mage_Sales_Model_Order)) {
			$quoteId = Mage::getSingleton('checkout/session')->getlastQuoteId();
			if ($quoteId) {
				$this->_orders = Mage::getModel('sales/order')->getCollection()->addFilter('quote_id', $quoteId);
			}
		}
		return $this->_orders;
	}

	/**
	 * Get the beacon URL.
	 * @return string | null
	 */
	public function getBeaconUrls()
	{
		$orders = $this->_getOrders();

		$urls = array();

		foreach($orders as $order) {
			if ($order instanceof Mage_Sales_Model_Order) {
				if (Mage::helper('pepperjam_network/config')->isItemizedOrders()) {
					$params = $this->_buildItemizedParams($order);
				} elseif (Mage::helper('pepperjam_network/config')->isDynamicOrders()) {
					$params = $this->_buildDynamicParams($order);
				} else {
					$params = $this->_buildBasicParams($order);
				}

				$urls[] = Mage::helper('pepperjam_network')->buildBeaconUrl($params);
			}
		}
		return $urls;
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
		if (($clickIdString = $this->_getHelper()->getClickIds()) != '') {
			$params = array_merge($params, array(static::KEY_CLICKID => $clickIdString));
		}
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
        $childPrice = array();
		foreach ($order->getAllItems() as $item) {

            $total = $item->getRowTotal() - $item->getDiscountAmount();
            $quantity = $item->getQtyOrdered();

            // Bundle lines are ignored, only sub-items included.
            // Fixed price will calculate average per item,
            if ($item->getProductType() === Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                if ($item->getProduct()->getPriceType() == Mage_Bundle_Model_Product_Price::PRICE_TYPE_FIXED) {
                    $qty = 0;
                    foreach ($item->getChildrenItems() as $childrenItem) {
                        $qty += $childrenItem->getQtyOrdered();
                    }
                    $childPrice[$item->getId()] = $total/$qty;
                }
                continue;
            }

            // Configurable lines are ignored, only sub-items included
            if ($item->getProductType() === Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                    $childPrice[$item->getId()] = $total / $quantity;
                continue;
            }

            // Construct item
            if ($item->getParentItem()) {
                $parentId = $item->getParentItem()->getItemId();
                $total = array_key_exists($parentId, $childPrice) ? $childPrice[$parentId] : $total;
            } else {
                $total = $total / $quantity;
            }
            $params = array_merge($params, array(
                static::KEY_ITEM . $increment => $item->getSku(),
                static::KEY_QTY . $increment => $quantity,
                static::KEY_AMOUNT . $increment => number_format($total, 2, '.', '')
            ));

            $increment++;
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
				$this->_getOrders() instanceof Mage_Sales_Model_Resource_Order_Collection
			) &&
			(
				$this->_getHelper()->isValidCookie() ||
				!$config->isAttributionEnabled()
			)
		);
	}
}
