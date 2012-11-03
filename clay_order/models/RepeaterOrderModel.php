<?php
/**
 * 受注のデータモデルです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Plugins
 * @package   Shop
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Order_RepeaterOrderModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Order");
		parent::__construct($loader->loadTable("RepeaterOrdersTable"), $values);
	}
	
	public function reconstruct(){
		// データを再構築する。
		Clay_Database_Factory::begin("order");
		try{
			$connection = Clay_Database_Factory::getConnection("order");
			$connection->query("TRUNCATE `shop_repeater_orders`");
			$sql = "INSERT INTO `shop_repeater_orders` SELECT `shop_orders`.*, count(`counter`.`order_id`) AS `order_repeat`";
			$sql .= " FROM `shop_orders` LEFT JOIN `shop_orders` AS `counter` ON `shop_orders`.`order_email` = `counter`.`order_email` AND `shop_orders`.`order_time` > `counter`.`order_time`";
			$sql .= " GROUP BY `shop_orders`.`order_id` ORDER BY count(`counter`.`order_id`)";
			$connection->query($sql);
			Clay_Database_Factory::commit("order");
		}catch(Expception $e){
			Clay_Database_Factory::rollback("order");
		}
		
	}
	
	public function save(){
		throw new SystemException("This Table is not writable");
	}
	
	function findByPrimaryKey($order_id){
		$this->findBy(array("order_id" => $order_id));
	}
	
	function findByCode($order_code){
		$this->findBy(array("order_code" => $order_code));
	}
	
	function findAllByCustomer($customer_id){
		return $this->findAllBy(array("customer_id" => $customer_id));
	}
	
	function packages(){
		$loader = new Clay_Plugin("Shop");
		$orderPackage = $loader->loadModel("OrderPackageModel");
		return $orderPackage->findAllByOrder($this->order_id);
	}
	
	function payments(){
		$loader = new Clay_Plugin("Shop");
		$orderPayment = $loader->loadModel("OrderPaymentModel");
		return $orderPayment->findAllByOrder($this->order_id);
	}
	
	protected function appendWhere($select, $key, $value){
		if(strpos($key, ":") > 0){
			list($op, $key2, $default) = explode(":", $key, 3);
			if($key2 == "order_time" && preg_match("/^[0-9]+-[0-9]+-[0-9]+$/", $value) > 0){
				switch($op){
					case "gt":
					case "ge":
						$value = date("Y-m-d 00:00:00", strtotime($value));
						break;
					case "lt":
					case "le":
						$value = date("Y-m-d 23:59:59", strtotime($value));
						break;
				}
			}
		}
		return parent::appendWhere($select, $key, $value);
	}
}
?>