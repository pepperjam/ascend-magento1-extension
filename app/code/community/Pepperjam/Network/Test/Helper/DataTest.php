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

class Pepperjam_Network_Test_Helper_DataTest extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test that Pepperjam_Network_Helper_Data::buildBeaconUrl yields the
	 * expected, escaped url with query string when passed an array of params to append.
	 */
	public function testBuildBeaconUrl()
	{
		$beaconUrl = 'https://example.com/track';
		$params = array('KEY1' => 'K1', 'KEY2' => 'K2');
		$result = $beaconUrl . '?' . http_build_query($params);

		$cfgHelper = $this->getHelperMock('pepperjam_network/config', array('getBeaconBaseUrl'));
		$cfgHelper->expects($this->once())
			->method('getBeaconBaseUrl')
			->will($this->returnValue($beaconUrl));
		$this->replaceByMock('helper', 'pepperjam_network/config', $cfgHelper);

		$this->assertSame($result, Mage::helper('pepperjam_network/data')->buildBeaconUrl($params));
	}

	/**
	 * @dataProvider dataProvider
	 */
	public function testIsValidCookie($cookie, $value, $expectedResult)
	{
		$dataHelper = $this->getHelperMock('pepperjam_network', array('getSourceCookieName'));
		$dataHelper->expects(($this->any()))
			->method('getSourceCookieName')
			->will($this->returnValue('ebay_enterprise_affiliate_source'));
		$this->replaceByMock('helper', 'pepperjam_network', $dataHelper);

		$_COOKIE[$cookie] = $value;

		$this->assertEquals((bool)$expectedResult, $dataHelper->isValidCookie());
	}
}
