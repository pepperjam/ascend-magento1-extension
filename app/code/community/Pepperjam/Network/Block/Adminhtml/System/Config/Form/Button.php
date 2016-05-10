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
 *
 */

class Pepperjam_Network_Block_Adminhtml_System_Config_Form_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('pepperjam_network/system/config/button.phtml');
	}

	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		return $this->_toHtml();
	}

	public function getAjaxUninstallUrl()
	{
		return Mage::helper('adminhtml')->getUrl('adminhtml/pepperjamNetwork/uninstall');
	}

	public function getButtonHtml()
	{
		$button = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(
			array(
				'id' => 'pepperjam_network_uninstall',
				'label' => $this->helper('adminhtml')->__('Uninstall'),
				'onclick' => 'javascript:uninstall(); return false;',
			));

		return $button->toHtml();
	}
}
