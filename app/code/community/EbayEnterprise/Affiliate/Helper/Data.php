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
}
