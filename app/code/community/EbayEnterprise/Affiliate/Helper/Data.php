<?php

class EbayEnterprise_Affiliate_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Build the beacon url given an array keys
	 * @param array $params
	 * @return string
	 */
	public function buildBeaconUrl(array $params)
	{
		return Mage::helper('eems_affiliate/config')->getBeaconBaseUrl() . '?' .
			http_build_query($params);
	}
	/**
	 * Get all unique configured program ids. Program ids may only be set at the
	 * website level, so only get the program id for the default store for
	 * each website.
	 * @return array
	 */
	public function getAllProgramIds()
	{
		$config = Mage::helper('eems_affiliate/config');
		return array_unique(array_map(
			function ($website) use ($config) {
				return $config->getProgramId($website->getDefaultStore());
			},
			Mage::app()->getWebsites()
		));
	}
	/**
	 * Get a single store view for a program id. As program ids are configured
	 * only at the global or website level, the store view selecetd will be
	 * the default store view for the scope the configuration is set at. When
	 * set globally, the default store view for the Magento instance will be
	 * selected. When set at a website level, the default store view for that
	 * website will be used.
	 * @param  string $programId
	 * @return Mage_Core_Model_Store|null
	 */
	public function getStoreForProgramId($programId)
	{
		$config = Mage::helper('eems_affiliate/config');
		// Check for the default store view to be this program id first, will match
		// when the program id is set at the global level.
		$defaultStoreView = Mage::app()->getDefaultStoreView();
		$defaultProgramId = $config->getProgramId($defaultStoreView);
		if ($programId === $defaultProgramId) {
			return $defaultStoreView;
		}
		// When set at the website level, use the first website encountered
		// with a matching program id
		foreach (Mage::app()->getWebsites() as $website) {
			$storeView = $website->getDefaultStore();
			if ($config->getProgramId($storeView) === $programId) {
				return $storeView;
			}
		}
		return null;
	}
	/**
	 * Get all store views that have a program id that matches the given
	 * program id
	 * @param  string $programId
	 * @return Mage_Core_Model_Store[]
	 */
	public function getAllStoresForProgramId($programId)
	{
		$config = Mage::helper('eems_affiliate/config');
		return array_filter(
			Mage::app()->getStores(),
			function ($store) use ($config, $programId) {
				return $config->getProgramId($store) === $programId;
			}
		);
	}
	/**
	 * take a boolean value and return the string 'yes' or 'no' when the boolean
	 * value is true or false
	 * @param bool $value
	 * @return string
	 * @codeCoverageIgnore
	 */
	public function parseBoolToYesNo($value)
	{
		return $value?'yes':'no';
	}
}
