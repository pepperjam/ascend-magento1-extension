<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Ordertypes
{
    public function toOptionArray()
    {
        $helper = Mage::helper('eems_affiliate');
        $config = Mage::helper('eems_affiliate/config');

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