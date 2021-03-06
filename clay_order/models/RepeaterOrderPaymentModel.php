<?php
/**
 * 受注決済のデータモデルです。
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
class Order_RepeaterOrderPaymentModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Order");
		parent::__construct($loader->loadTable("RepeaterOrderPaymentsTable"), $values);
	}

	public function reconstruct(){
		// データを再構築する。
		Clay_Database_Factory::begin("order");
		try{
			$connection = Clay_Database_Factory::getConnection("order");
			$connection->query("TRUNCATE `shop_repeater_order_payments`");
			$sql = "INSERT INTO `shop_repeater_order_payments` SELECT `shop_orders`.`order_email` AS `order_email`,`shop_order_payments`.`order_payment_id` AS `order_payment_id`,`shop_order_payments`.`order_id` AS `order_id`,";
			$sql .= "`shop_order_payments`.`payment_id` AS `payment_id`,`shop_order_payments`.`payment_card_company` AS `payment_card_company`,";
			$sql .= "`shop_order_payments`.`payment_card_no` AS `payment_card_no`,`shop_order_payments`.`payment_card_name` AS `payment_card_name`,";
			$sql .= "`shop_order_payments`.`payment_card_expires` AS `payment_card_expires`,`shop_order_payments`.`payment_card_split` AS `payment_card_split`,";
			$sql .= "`shop_order_payments`.`payment_card_split_description` AS `payment_card_split_description`,`shop_order_payments`.`payment_bank_name` AS `payment_bank_name`,";
			$sql .= "`shop_order_payments`.`payment_bank_branch` AS `payment_bank_branch`,`shop_order_payments`.`payment_bank_account_type` AS `payment_bank_account_type`,";
			$sql .= "`shop_order_payments`.`payment_bank_account` AS `payment_bank_account`,`shop_order_payments`.`payment_convinience_name` AS `payment_convinience_name`,";
			$sql .= "`shop_order_payments`.`payment_convinience_code` AS `payment_convinience_code`,`shop_order_payments`.`charge` AS `charge`,`shop_order_payments`.`payment_total` AS `payment_total`,";
			$sql .= "`shop_order_payments`.`payment_status` AS `payment_status`,`shop_order_payments`.`create_time` AS `create_time`,`shop_order_payments`.`update_time` AS `update_time`,";
			$sql .= "COUNT(`counter`.`order_id`) AS `order_repeat` ";
			$sql .= " FROM `shop_order_payments` INNER JOIN `shop_orders` ON `shop_order_payments`.`order_id` = `shop_orders`.`order_id`";
			$sql .= " LEFT JOIN `shop_orders` AS `counter` ON `shop_orders`.`order_email` = `counter`.`order_email` and `shop_orders`.`order_time` > `counter`.`order_time`";
			$sql .= " GROUP BY `shop_order_payments`.`order_payment_id` ORDER BY count(`counter`.`order_id`)";
			$connection->query($sql);
			Clay_Database_Factory::commit("order");
		}catch(Expception $e){
			Clay_Database_Factory::rollback("order");
		}
	}
	
	function findByPrimaryKey($order_payment_id){
		$this->findBy(array("order_payment_id" => $order_payment_id));
	}
	
	function findAllByOrder($order_id){
		return $this->findAllBy(array("order_id" => $order_id));
	}
	
	function payment(){
		$loader = new Clay_Plugin("Order");
		$payment = $loader->loadModel("PaymentModel");
		$payment->findByPrimaryKey($this->payment_id);
		return $payment;
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