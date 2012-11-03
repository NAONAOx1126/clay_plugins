<?php
/**
 * 顧客情報のモデルクラス
 */
class TempOrderModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Order");
		parent::__construct($loader->loadTable("TempOrdersTable"), $values);
	}
	
	function findByPrimaryKey($order_id){
		$this->findBy(array("order_id" => $order_id));
	}
	
	function findByOrderCode($order_code){
		$this->findBy(array("order_code" => $order_code));
	}
}
?>