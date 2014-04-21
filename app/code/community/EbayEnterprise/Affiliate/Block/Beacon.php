<?php
class EbayEnterprise_Affiliate_Block_Beacon extends Mage_Core_Block_Template
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
	 * @var Mage_Sales_Model_Order
	 * @see self::_getOrder
	 */
	protected $_order;
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
		return ($order instanceof Mage_Sales_Model_Order) ?
			Mage::helper('eems_affiliate')->buildBeaconUrl(
				Mage::helper('eems_affiliate/config')->isItemizedOrders() ?
					$this->_buildItemizedParams($order) : $this->_buildBasicParams($order)
			) : null;
	}
	/**
	 * build common params array
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildCommonParams(Mage_Sales_Model_Order $order)
	{
		$params = array(
			static::KEY_PID => Mage::helper('eems_affiliate/config')->getProgramId(),
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
		return array_merge($this->_buildCommonParams($order), array(
			static::KEY_AMOUNT => round($order->getSubtotal(), 2),
			static::KEY_TYPE => Mage::helper('eems_affiliate/config')->getTransactionType()
		));
	}
	/**
	 * build itemized order params array for itemized beacon URL
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildItemizedParams(Mage_Sales_Model_Order $order)
	{
		$params = array(static::KEY_INT => Mage::helper('eems_affiliate/config')->getInt());
		$increment = 1; // incrementer for the unique item keys
		foreach ($order->getAllVisibleItems() as $item) {
			$position = $this->_getDupePosition($params, $item);
			$quantity = (int) $item->getQtyOrdered();
			$total = round($item->getRowTotal(), 2);
			if ($position) {
				// we detected that the current item already exist in the params array
				// and have the key increment position let's simply adjust
				// the qty and total amount
				$params[static::KEY_QTY . $position] += $quantity;
				$params[static::KEY_TOTALAMOUNT . $position] += $total;
			} else {
				$params = array_merge($params, array(
					static::KEY_ITEM . $increment => $item->getSku(),
					static::KEY_QTY . $increment => $quantity,
					static::KEY_TOTALAMOUNT . $increment => $total
				));
				$increment++; // only get incremented when a unique key have been appended
			}
		}
		return array_merge($this->_buildCommonParams($order), $params);
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
	 * @return bool
	 */
	public function showBeacon()
	{
		return (
			Mage::helper('eems_affiliate/config')->isEnabled() &&
			$this->_getOrder() instanceof Mage_Sales_Model_Order
		);
	}
}
