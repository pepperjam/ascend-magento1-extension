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

class Pepperjam_Network_Model_System_Config_Source_Trackingmethods
{
	public function toOptionArray()
	{
		$helper = Mage::helper('pepperjam_network');
		$config = Mage::helper('pepperjam_network/config');

		return array(
			array(
				'value' => $config::TRACKING_METHOD_PIXEL,
				'label' => $helper->__('Pixel'),
			),
			array(
				'value' => $config::TRACKING_METHOD_FEED,
				'label' => $helper->__('Feed'),
			),
		);
	}
}
