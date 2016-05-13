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

$orderTable = $installer->getTable('sales/order');

$installer->getConnection()
	->addColumn($orderTable, 'network_source', array(
		'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
		'length'   => 20,
		'nullable' => TRUE,
		'unsigned' => TRUE,
		'comment'  => "Pepperjam Network Source"
	));

$installer->getConnection()
	->addColumn($orderTable, 'network_click_id', array(
		'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
		'nullable' => TRUE,
		'unsigned' => TRUE,
		'comment'  => "Pepperjam Network Click ID"
	));

$installer->getConnection()
	->addColumn($orderTable, 'network_publisher_id', array(
		'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
		'nullable' => TRUE,
		'unsigned' => TRUE,
		'comment'  => "Pepperjam Network Publisher ID"
	));

$installer->endSetup();
