<?php

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
