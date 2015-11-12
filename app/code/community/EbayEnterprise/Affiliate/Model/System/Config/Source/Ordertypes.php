<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Ordertypes
{
	public function toOptionArray()
	{
		$helper = Mage::helper('eems_affiliate');

		return array(
			array(
				'value' => 'basic',
				'label' => $helper->__('Basic'),
			),
			array(
				'value' => 'itemized',
				'label' => $helper->__('Itemized'),
			),
			array(
				'value' => 'dynamic',
				'label' => $helper->__('Dynamic'),
			),
		);
	}
}