<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Visibilityyesno
{
	/**
	 * Get product visibility list
	 * @return array
	 */
	public function toOptionArray()
	{
		$helper = Mage::helper('eems_affiliate');
		return array(
			array('value' => '', 'label' => ''),
			array('value' => 'visibility', 'label' => $helper->__('Yes/no'))
		);
	}
}
