<?php
/**
 * @codeCoverageIgnore
 */
class EbayEnterprise_Affiliate_Helper_Config
{
	const BEACON_URL_PATH = 'marketing_solutions/eems_affiliate/beacon_url';
	const ENABLED_PATH = 'marketing_solutions/eems_affiliate/active';
	const INT_PATH = 'marketing_solutions/eems_affiliate/int';
	const ITEMIZED_ORDERS_PATH = 'marketing_solutions/eems_affiliate/itemized_orders';
	const PROGRAM_ID_PATH = 'marketing_solutions/eems_affiliate/program_id';
	const TRANSACTION_TYPE_PATH = 'marketing_solutions/eems_affiliate/transaction_type';
	/**
	 * retrieve the program id from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getProgramId($store=null)
	{
		return Mage::getStoreConfig(static::PROGRAM_ID_PATH, $store);
	}
	/**
	 * retrieve the transaction type from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getTransactionType($store=null)
	{
		return Mage::getStoreConfig(static::TRANSACTION_TYPE_PATH, $store);
	}
	/**
	 * retrieve the itemized orders from store config
	 * @param mixed $store
	 * @return bool
	 */
	public function isItemizedOrders($store=null)
	{
		return Mage::getStoreConfigFlag(static::ITEMIZED_ORDERS_PATH, $store);
	}
	/**
	 * check if beacon pixel is enable in the store config
	 * @param mixed $store
	 * @return bool
	 */
	public function isEnabled($store=null)
	{
		return Mage::getStoreConfigFlag(static::ENABLED_PATH, $store);
	}
	/**
	 * retrieve the int from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getInt($store=null)
	{
		return Mage::getStoreConfig(static::INT_PATH, $store);
	}
	/**
	 * retrieve the base url of the beacon from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getBeaconBaseUrl($store=null)
	{
		return Mage::getStoreConfig(static::BEACON_URL_PATH, $store);
	}
}
