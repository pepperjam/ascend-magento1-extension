<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Categories
{
	/**
	 * Get category attributes
	 * @return array
	 */
	public function toOptionArray()
	{
		$helper = Mage::helper('eems_affiliate');
		return array(
			array('value' => '', 'label' => ''),
			array('value' => 'category', 'label' => $helper->__('Category'))
		);
	}
}
