<?php

class Pepperjam_Network_Model_System_Config_Source_Ordertypes
{
	public function toOptionArray()
	{
		$helper = Mage::helper('pepperjam_network');
		$config = Mage::helper('pepperjam_network/config');

		return array(
			array(
				'value' => $config::ORDER_TYPE_BASIC,
				'label' => $helper->__('Basic'),
			),
			array(
				'value' => $config::ORDER_TYPE_ITEMIZED,
				'label' => $helper->__('Itemized'),
			),
			array(
				'value' => $config::ORDER_TYPE_DYNAMIC,
				'label' => $helper->__('Dynamic'),
			),
		);
	}
}
