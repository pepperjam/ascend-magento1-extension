<?php

class EbayEnterprise_Affiliate_Test_Model_Feed_Order_BasicTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test getting the fields to include in the feed. Should be pulling the
	 * comma-separated list of fields from config.xml and splitting it to produce
	 * an array of fields.
	 * @return array
	 */
	public function testFeedFields()
	{
		$config = $this->getHelperMock('eems_affiliate/config', array('getBasicOrderFeedFields'));
		$config->expects($this->any())
			->method('getBasicOrderFeedFields')
			->will($this->returnValue('one,two,three'));
		$this->replaceByMock('helper', 'eems_affiliate/config', $config);
		$feed = Mage::getModel('eems_affiliate/feed_order_basic');
		$this->assertSame(
			array('one', 'two', 'three'),
			EcomDev_Utils_Reflection::invokeRestrictedMethod($feed, '_getFeedFields')
		);
	}
	/**
	 * Test generating the file name using the
	 * @test
	 */
	public function testGetFileName()
	{
		$format = '%s_basic_orders_%s.csv';
		$programId = 'PROGRAM_ID';
		$time = 1397829577;
		$formattedTime = date('YmdHis', $time);
		$fileName = "{$programId}_basic_orders_{$formattedTime}.csv";

		// get the default store to be used as context for config getting
		$store = Mage::app()->getStore();
		// get the order feed instance with the expected store and start time injected
		$feed = Mage::getModel('eems_affiliate/feed_order_basic', array('store' => $store, 'start_time' => $time));

		$config = $this->getHelperMock('eems_affiliate/config', array('getBasicOrderFeedFileFormat', 'getProgramId'));
		// Don't expect to have different file name formats for each program id so
		// store context doesn't matter when getting this config value
		$config->expects($this->any())
			->method('getBasicOrderFeedFileFormat')
			->will($this->returnValue($format));
		// Will have different program ids per feed so store context definitely
		// matters when getting this value.
		$config->expects($this->any())
			->method('getProgramId')
			->with($this->identicalTo($store))
			->will($this->returnValue($programId));
		$this->replaceByMock('helper', 'eems_affiliate/config', $config);

		$generated = EcomDev_Utils_Reflection::invokeRestrictedMethod($feed, '_getFileName');
		$this->assertSame(
			$fileName,
			$generated
		);
	}
}
