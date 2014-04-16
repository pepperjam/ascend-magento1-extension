<?php
class EbayEnterprise_Affiliate_Block_Beacon extends Mage_Core_Block_Template
{
	const KEY_PID = 'PID';
	const KEY_OID = 'OID';
	const KEY_AMOUNT = 'AMOUNT';
	const KEY_TYPE = 'TYPE';
	const KEY_QTY = 'QTY';
	const KEY_TOTALAMOUNT = 'TOTALAMOUNT';
	const KEY_INT = 'INT';
	const KEY_ITEM = 'ITEM';
	const KEY_PROMOCODE = 'PROMOCODE';
	/**
	 * @var Mage_Sales_Model_Order
	 */
	protected $_order;
	/**
	 * Get the last order.
	 * @return Mage_Sales_Model_Order
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
	 * Get the beacon url.
	 * @return String
	 */
	public function getBeaconUrl()
	{
		Mage::log('getting beacon url');
		$order = $this->_getOrder();
		return Mage::helper('eems_affiliate')->buildBeaconUrl(
			Mage::helper('eems_affiliate/config')->isItemizedOrders() ?
				$this->_buildItemizeParams($order): $this->_buildBasicParams($order)
		);
	}
	/**
	 * build common params array
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildCommonParams(Mage_Sales_Model_Order $order)
	{
		$couponCode = trim($order->getCouponCode());
		return array_merge(
			array(
				static::KEY_PID => Mage::helper('eems_affiliate/config')->getProgramId(),
				static::KEY_OID => $order->getIncrementId(),
			),
			($couponCode !== '')? array(static::KEY_PROMOCODE => $couponCode) : array()
		);
	}
	/**
	 * build basic params array for non iteminized beacon url
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildBasicParams($order)
	{
		return array_merge($this->_buildCommonParams($order), array(
			static::KEY_AMOUNT => round($order->getSubtotal(), 2),
			static::KEY_TYPE => Mage::helper('eems_affiliate/config')->getTransactionType()
		));
	}
	/**
	 * build itemized order params array for itemized beacon url
	 * @param Mage_Sales_Model_Order $order
	 * @return array
	 */
	protected function _buildItemizeParams(Mage_Sales_Model_Order $order)
	{
		$params = array(static::KEY_INT => Mage::helper('eems_affiliate/config')->getInt());
		$increment = 1;
		foreach ($order->getAllVisibleItems() as $item) {
			$position = $this->_getDupePosition($params, $item);
			if ($position) {
				$params[static::KEY_QTY . $position] *= (int) $item->getQtyOrdered();
				$params[static::KEY_TOTALAMOUNT . $position] *= (int) $item->getQtyOrdered();
			} else {
				$params = array_merge($params, array(
					static::KEY_ITEM . $increment => $item->getSku(),
					static::KEY_QTY . $increment => (int) $item->getQtyOrdered(),
					static::KEY_TOTALAMOUNT . $increment => round($item->getRowTotal(), 2)
				));
				$increment++;
			}
		}
		return array_merge($this->_buildCommonParams($order), $params);
	}
	/**
	 * check if the current sku already exist in the params data if so return
	 * the position it is found in
	 * @param array $params
	 * @param Mage_Sales_Model_Order_Item $item
	 * @return int the item position where dupe found otherwise zero
	 */
	protected function _getDupePosition(array $params, Mage_Sales_Model_Order_Item $item)
	{
		$i = 1;
		while(isset($params[static::KEY_ITEM . $i])) {
			if ($params[static::KEY_ITEM . $i] === $item->getSku()) {
				return $i;
			}
			$i++;
		}
		return 0;
	}
	/**
	 * Whether or not to display the beacon.
	 * @return bool
	 */
	public function showBeacon()
	{
		Mage::log('check show beacon');
		if (!Mage::helper('eems_affiliate/config')->isEnabled()) {
			Mage::log('disabled');
			return false;
		}

		$order = $this->_getOrder();
		Mage::log($order instanceof Mage_Sales_Model_Order);
		return (!($order instanceof Mage_Sales_Model_Order))? false : true;
	}
}
