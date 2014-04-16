<?php
class EbayEnterprise_Affiliate_Test_Helper_DataTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test EbayEnterprise_Affiliate_Helper_Data::buildBeaconUrl method for the
	 * following expectations
	 * Expectation 1: this test will invoked the method
	 *                EbayEnterprise_Affiliate_Helper_Data::buildBeaconUrl given
	 *                an array of key params and values in which it will invoked
	 *                the method EbayEnterprise_Affiliate_Helper_Config::getBeaconBaseUrl
	 *                and concatenating the getBeaconBaseUrl return value with the given
	 *                array keys and values
	 */
	public function testBuildBeaconUrl()
	{
		$beaconUrl = 'https://example.com/track';
		$params = array('KEY1' => 'K1', 'KEY2' => 'K2');
		$result = $beaconUrl . '?' . http_build_query($params);

		$cfgHelper = $this->getHelperMock('eems_affiliate/config', array('getBeaconBaseUrl'));
		$cfgHelper->expects($this->once())
			->method('getBeaconBaseUrl')
			->will($this->returnValue($beaconUrl));
		$this->replaceByMock('helper', 'eems_affiliate/config', $cfgHelper);

		$this->assertSame($result, Mage::helper('eems_affiliate/data')->buildBeaconUrl($params));
	}
}
