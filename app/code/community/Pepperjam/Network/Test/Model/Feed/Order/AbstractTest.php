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

class Pepperjam_Network_Test_Model_Feed_Order_AbstractTest extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test generating the file name using the
	 */
	public function testGetFileName()
	{
		$format = '%s_corrected_orders_%s.csv';
		$programId = 'PROGRAM_ID';
		$time = 1397829577;
		$formattedTime = date('YmdHis', $time);
		$fileName = "{$programId}_corrected_orders_{$formattedTime}.csv";

		// get the default store to be used as context for config getting
		$store = Mage::app()->getStore();
		// get the order feed instance with the expected store and start time injected
		$feed = $this->getModelMock(
			'pepperjam_network/feed_order_itemized',
			array('_getFileNameFormat'),
			true,
			array(array('store' => $store, 'start_time' => $time))
		);
		$feed->expects($this->any())
			->method('_getFileNameFormat')
			->will($this->returnValue($format));

		$config = $this->getHelperMock('pepperjam_network/config', array('getProgramId'));
		// Will have different program ids per feed so store context definitely
		// matters when getting this value.
		$config->expects($this->any())
			->method('getProgramId')
			->with($this->identicalTo($store))
			->will($this->returnValue($programId));
		$this->replaceByMock('helper', 'pepperjam_network/config', $config);

		$generated = EcomDev_Utils_Reflection::invokeRestrictedMethod($feed, '_getFileName');
		$this->assertSame(
			$fileName,
			$generated
		);
	}
}
