<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Stockyesno
{
	/**
	 * Get in stock list
	 * @return array
	 */
	public function toOptionArray()
	{
		$helper = Mage::helper('eems_affiliate');
		return array(
			array('value' => '', 'label' => ''),
			array('value' => 'in_stock', 'label' => $helper->__('Yes/no'))
		);
	}
}
