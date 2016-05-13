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

$installer->setConfigData('pepperjam/pepperjam_network/order_correction_feed/last_run_time', Mage::getStoreConfig('pepperjam/pepperjam_network/feed/last_run_time'));

$installer->setConfigData('pepperjam/pepperjam_network/order_feed/last_run_time', time());
$installer->setConfigData('pepperjam/pepperjam_network/order_correction_feed/last_run_time', Mage::getStoreConfig('pepperjam/pepperjam_network/feed/last_run_time'));
$installer->deleteConfigData('pepperjam/pepperjam_network/feed/last_run_time');

$installer->endSetup();
