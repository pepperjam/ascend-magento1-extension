<?php
class EbayEnterprise_Affiliate_Test_Helper_DataTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test that EbayEnterprise_Affiliate_Helper_Data::buildBeaconUrl yields the
	 * expected, escaped url with query string when passed an array of params to append.
	 * @test
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
	/**
	 * Test that EbayEnterprise_Affiliate_Helper_Data::getWebsitesDefaultStoreviews
	 * will return an array of key program id value website default store view per
	 * websites
	 * @test
	 */
	public function testGetWebsitesDefaultStoreViews()
	{
		$stores = array();
		foreach (range(1, 4) as $store) {
			$stores[$store < 3? 1 : 2][$store] = Mage::getModel('core/store')->setId($store);
		}

		$programIds = array('P1', 'P2');

		$websites = array();
		foreach (range(1, 2) as $website) {
			$groups = array();
			foreach (range(1, 2) as $group) {
				$storeObj = Mage::getModel('core/store_group');
				$storeObj->setId($group)->setStores($stores[$website]);
				$groups[$group] = $storeObj;
			}
			$websiteObj = Mage::getModel('core/website');
			$websiteObj->addData(array('entity_id' => $website))->setGroups($groups);
			$websites[$website] = $websiteObj;
		}
		$app = $this->getModelMockBuilder('core/app')
			->disableOriginalConstructor()
			->setMethods(array('getWebsites'))
			->getMock();
		$app->expects($this->once())
			->method('getWebsites')
			->will($this->returnValue($websites));

		$helper = $this->getHelperMock('eems_affiliate/data', array('getApp'));
		$helper->expects($this->once())
			->method('getApp')
			->will($this->returnValue($app));

		$config = $this->getHelperMock('eems_affiliate/config', array('getProgramId'));
		$config->expects($this->exactly(2))
			->method('getProgramId')
			->will($this->returnValueMap(array(
				array($stores[1][1], $programIds[0]),
				array($stores[2][3], $programIds[1])
			)));
		$this->replaceByMock('helper', 'eems_affiliate/config', $config);

		$result = array_combine($programIds, array($stores[1][1], $stores[2][3]));

		$this->assertSame($result, $helper->getWebsitesDefaultStoreviews());
	}
}
