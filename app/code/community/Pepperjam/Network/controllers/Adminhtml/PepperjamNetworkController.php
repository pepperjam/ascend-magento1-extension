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

class Pepperjam_Network_Adminhtml_PepperjamNetworkController extends Mage_Adminhtml_Controller_Action
{
	const ATTRUBUTE_ID = 'commissioning_category';
	const DEFAULT_SCONFIG_FILENAME = 'cache.cfg';
	const PACKAGE_CHANNEL = 'community';
	const PACKAGE_NAME = 'Pepperjam_Network';

	protected $_config;
	protected $_sconfig;

	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('admin');
	}

	public function uninstallAction()
	{
		// Remove commissioning category
		$setup = new Mage_Eav_Model_Entity_Setup('pepperjam_network_setup');
		$setup->startSetup();

		$objCatalogEavSetup = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');

		$attributeExists = (bool) $objCatalogEavSetup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, self::ATTRUBUTE_ID);
		if ($attributeExists) {
			$setup->removeAttribute(Mage_Catalog_Model_Product::ENTITY, self::ATTRUBUTE_ID);
		}

		$setup->endSetup();

		// Send message to admin
		Mage::getSingleton('core/session')->addSuccess('Package ' . self::PACKAGE_CHANNEL . '/' . self::PACKAGE_NAME . ' is ready to uninstall');
	}
}
