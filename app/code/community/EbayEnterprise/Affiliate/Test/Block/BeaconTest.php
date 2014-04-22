<?php
class EbayEnterprise_Affiliate_Test_Block_BeaconTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Get a mock of the config helper to return expected system configurations.
	 * @param  string $programId
	 * @param  string $transactionType
	 * @param  string $itemizeOrders
	 * @return Mock_EbayEnterprise_Affiliate_Helper_Config
	 */
	protected function _getConfigHelper($programId, $transactionType, $itemizeOrders)
	{
		$helper = $this->getHelperMock('eems_affiliate/config', array(
			'getProgramId', 'getTransactionType', 'isItemizedOrders'
		));
		$helper->expects($this->any())
			->method('getProgramId')
			->will($this->returnValue($programId));
		$helper->expects($this->any())
			->method('getTransactionType')
			->will($this->returnValue($transactionType));
		$helper->expects($this->any())
			->method('isItemizedOrders')
			->will($this->returnValue($itemizeOrders));
		return $helper;
	}
	/**
	 * Test building the basic beacon url. Should only include order level
	 * information about the order in the url params.
	 * @test
	 */
	public function testGetBeaconUrlBasic()
	{
		$orderId = '000012';
		$subtotal = 10.99;
		$couponCode = 'COUPON';
		$programId = 'PROGRAM ID';
		$transactionType = 'TRANS TYPE';
		$itemizeOrders = false;
		$params = array(
			'PID' => $programId,
			'OID' => $orderId,
			'PROMOCODE' => $couponCode,
			'AMOUNT' => $subtotal,
			'TYPE' => $transactionType
		);
		$beaconUrl = 'https://example.com/track';
		$order = Mage::getModel('sales/order', array(
			'increment_id' => $orderId,
			'subtotal' => $subtotal,
			'coupon_code' => $couponCode
		));
		// mock out the config helper to setup expected system configurations
		$this->replaceByMock(
			'helper',
			'eems_affiliate/config',
			$this->_getConfigHelper($programId, $transactionType, $itemizeOrders)
		);
		$helper = $this->getHelperMock('eems_affiliate/data', array('buildBeaconUrl'));
		$helper->expects($this->once())
			->method('buildBeaconUrl')
			->with($this->identicalTo($params))
			->will($this->returnValue($beaconUrl));
		$this->replaceByMock('helper', 'eems_affiliate', $helper);

		$block = $this->getBlockMock('eems_affiliate/beacon', array('_getOrder'));
		$block->expects($this->any())
			->method('_getOrder')
			->will($this->returnValue($order));

		$this->assertSame($beaconUrl, $block->getBeaconUrl());
	}
	/**
	 * Test building the itemized beacon url. Should include item level details
	 * in the url params for each item in the order.
	 * @test
	 */
	public function testGetBeaconUrlItemized()
	{
		$programId = 'PROGRAM ID';
		// should use itemized beacon when itemize orders is true
		$itemizeOrders = true;
		$orderId = '000012';
		$couponCode = 'COUPON';
		$itemSku = 'sku-12345';
		$altSku = 'sku-54321';
		$itemQty = 2;
		$altQty = 3;
		$itemAmt = 12.34;
		$altAmt = 10.67;

		// This is the expected final output from the method - gets returned from
		// the eems_affiliate/data helper when called with the right params.
		$beaconUrl = 'https://example.com/track?PARAM=Value';
		// These are the "correct" params the helper method should be called with.
		// Order of the array is significant - both to the test and the
		// actual implementation.
		$beaconParams = array(
			'PID' => $programId,
			'OID' => $orderId,
			'PROMOCODE' => $couponCode,
			'INT' => 'ITEMIZED',
			'ITEM1' => $itemSku,
			'QTY1' => $itemQty * 2,
			'TOTALAMOUNT1' => $itemAmt * 2,
			'ITEM2' => $altSku,
			'QTY2' => $altQty,
			'TOTALAMOUNT2' => $altAmt
		);

		// setup some items and an order containing the items
		$item = Mage::getModel('sales/order_item', array(
			'sku' => $itemSku, 'qty_ordered' => $itemQty, 'row_total' => $itemAmt,
			'parent_item_id' => null,
		));
		// this item should be merged with the preceding item with the same sku
		$itemDupe = Mage::getModel('sales/order_item', array(
			'sku' => $itemSku, 'qty_ordered' => $itemQty, 'row_total' => $itemAmt,
			'parent_item_id' => null,
		));
		// parent item should be excluded
		$parentItem = Mage::getModel('sales/order_item', array(
			'sku' => 'some-other-sku', 'qty_ordered' => 1, 'row_total' => 12.00,
			'parent_item_id' => '23',
		));
		// this item should also be included
		$altItem = Mage::getModel('sales/order_item', array(
			'sku' => $altSku, 'qty_ordered' => $altQty, 'row_total' => $altAmt,
			'parent_item_id' => null,
		));
		$order = Mage::getModel('sales/order', array(
			'increment_id' => $orderId, 'coupon_code' => $couponCode
		));
		$order->addItem($item);
		$order->addItem($itemDupe);
		$order->addItem($parentItem);
		$order->addItem($altItem);

		// mock out the config helper to setup expected system configurations
		$this->replaceByMock(
			'helper',
			'eems_affiliate/config',
			$this->_getConfigHelper($programId, null, $itemizeOrders)
		);

		$helper = $this->getHelperMock('eems_affiliate/data', array('buildBeaconUrl'));
		$helper->expects($this->once())
			->method('buildBeaconUrl')
			->with($this->identicalTo($beaconParams))
			->will($this->returnValue($beaconUrl));
		$this->replaceByMock('helper', 'eems_affiliate', $helper);

		$block = $this->getBlockMock('eems_affiliate/beacon', array('_getOrder'));
		$block->expects($this->any())
			->method('_getOrder')
			->will($this->returnValue($order));

		$this->assertSame($beaconUrl, $block->getBeaconUrl());
	}
	/**
	 * Test that EbayEnterprise_Affiliate_Block_Beacon::_getOrder will be invoked
	 * by this test and expect it to return Mage_Sales_Model_Order object
	 * @test
	 */
	public function testGetOrder()
	{
		$orderId = '00000084848';
		$session = $this->getModelMockBuilder('checkout/session')
			->disableOriginalConstructor()
			->setMethods(null)
			->getMock();
		$session->setLastOrderId($orderId);
		$this->replaceByMock('singleton', 'checkout/session', $session);

		$order = $this->getModelMock('sales/order', array('load'));
		$order->expects($this->once())
			->method('load')
			->with($this->identicalTo($orderId))
			->will($this->returnSelf());
		$this->replaceByMock('model', 'sales/order', $order);

		$beacon = $this->getBlockMock('eems_affiliate/beacon', array());
		EcomDev_Utils_Reflection::setRestrictedPropertyValue($beacon, '_order', null);

		$this->assertSame($order, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$beacon, '_getOrder', array()
		));
	}
	/**
	 * Test that EbayEnterprise_Affiliate_Block_Beacon::showBeacon will return true
	 * when invoke by this test.
	 * @test
	 */
	public function testShowBeacon()
	{
		$result = true;
		$isEnabled = true;
		$order = Mage::getModel('sales/order');

		$configHelper = $this->getHelperMock('eems_affiliate/config', array('isEnabled'));
		$configHelper->expects($this->once())
			->method('isEnabled')
			->will($this->returnValue($isEnabled));
		$this->replaceByMock('helper', 'eems_affiliate/config', $configHelper);

		$beacon = $this->getBlockMock('eems_affiliate/beacon', array('_getOrder'));
		$beacon->expects($this->once())
			->method('_getOrder')
			->will($this->returnValue($order));

		$this->assertSame($result, $beacon->showBeacon());
	}
}