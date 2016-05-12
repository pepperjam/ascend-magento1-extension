<?php

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
