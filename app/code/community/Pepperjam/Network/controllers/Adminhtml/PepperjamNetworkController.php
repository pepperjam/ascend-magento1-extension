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

	public function uninstallAction()
	{
		// Remove commissioning category
		$setup = new Mage_Eav_Model_Entity_Setup('pepperjam_network_setup');
		$setup->startSetup();

		$objCatalogEavSetup = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');

		Mage::log(array('uninstallAction', get_class($objCatalogEavSetup)));
		$attributeExists = (bool) $objCatalogEavSetup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, self::ATTRUBUTE_ID);
		if ($attributeExists) {
			$setup->removeAttribute(Mage_Catalog_Model_Product::ENTITY, self::ATTRUBUTE_ID);
		}

		$setup->endSetup();

		// Uninstall extension
		Mage_Connect_Command::registerCommands(); // Must run or next line will fail
		$installer = Mage_Connect_Command::getInstance('uninstall');
		$installer->setFrontendObject(Mage_Connect_Frontend::getInstance('CLI'));
		$installer->setSconfig($this->getSingleConfig());

		$installer->doUninstall('uninstall', array(), array(self::PACKAGE_CHANNEL, self::PACKAGE_NAME));

		// Clear cache
		Mage::app()->cleanCache();

		// Send message to admin
		Mage::getSingleton('core/session')->addSuccess('Package ' . self::PACKAGE_CHANNEL . '/' . self::PACKAGE_NAME . ' successfully deleted');
	}

	/**
	 * Retrieve object of config and set it to Mage_Connect_Command
	 *
	 * @return Mage_Connect_Config
	 */
	public function getConfig()
	{
		if (!$this->_config) {
			$this->_config = new Mage_Connect_Config();
			$ftp=$this->_config->__get('remote_config');
			if(!empty($ftp)){
				$packager = new Mage_Connect_Packager();
				list($cache, $config, $ftpObj) = $packager->getRemoteConf($ftp);
				$this->_config=$config;
				$this->_sconfig=$cache;
			}
			$this->_config->magento_root = Mage::getBaseDir('base');
		    Mage_Connect_Command::setConfigObject($this->_config);
		}
		return $this->_config;
	}

	/**
	 * Retrieve object of single config and set it to Mage_Connect_Command
	 *
	 * @param bool $reload
	 * @return Mage_Connect_Singleconfig
	 */
	public function getSingleConfig($reload = false)
	{
		if(!$this->_sconfig || $reload) {
			$this->_sconfig = new Mage_Connect_Singleconfig(
				$this->getConfig()->magento_root . DIRECTORY_SEPARATOR
				. $this->getConfig()->downloader_path . DIRECTORY_SEPARATOR
				. self::DEFAULT_SCONFIG_FILENAME
			);
		}
		Mage_Connect_Command::setSconfig($this->_sconfig);
		return $this->_sconfig;

	}
}
