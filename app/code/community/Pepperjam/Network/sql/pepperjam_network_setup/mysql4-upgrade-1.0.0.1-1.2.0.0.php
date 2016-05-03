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

$installer = $this;

$installer->startSetup();

// Below based on http://stackoverflow.com/questions/9599262/magento-add-new-attribute-to-all-products
// Incremented version to get this to run for existing users.
$attrCode = 'commissioning_category';

$objCatalogEavSetup = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');
$attrIdTest = $objCatalogEavSetup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

if ($attrIdTest === false) {
	$objCatalogEavSetup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
		'group'                => 'General',
		'type'                 => 'int',
		'backend'              => '',
		'frontend'             => '',
		'label'                => 'Commissioning Category',
		'note'                 => 'Category that will be used for Pepperjam Network sales. If not set, one of the categories assigned to this product will be chosen.',
		'input'                => 'select',
		'class'                => '',
		'source'               => 'pepperjam_network/product_attribute_source_commissioningCategory',
		'global'               => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible'              => true,
		'required'             => false,
		'user_defined'         => false,
		'default'              => null,
		'visible_on_front'     => false,
		'unique'               => false,
		'is_configurable'      => false,
		'used_for_promo_rules' => false,
		'apply_to'             => 'simple,configurable,bundle,grouped',
	));
}

$installer->endSetup();
