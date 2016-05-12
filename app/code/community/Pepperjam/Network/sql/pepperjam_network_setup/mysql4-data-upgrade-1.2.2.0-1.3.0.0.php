<?php

$installer = $this;
$installer->startSetup();

$installer->setConfigData('pepperjam/pepperjam_network/order_correction_feed/last_run_time', Mage::getStoreConfig('pepperjam/pepperjam_network/feed/last_run_time'));

$installer->setConfigData('pepperjam/pepperjam_network/order_feed/last_run_time', time());
$installer->setConfigData('pepperjam/pepperjam_network/order_correction_feed/last_run_time', Mage::getStoreConfig('pepperjam/pepperjam_network/feed/last_run_time'));
$installer->deleteConfigData('pepperjam/pepperjam_network/feed/last_run_time');

$installer->endSetup();
