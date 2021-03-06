<?php
/**
 * 顧客拡張情報のモデルクラス
 */
class Member_CustomerOptionModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Member");
		parent::__construct($loader->loadTable("CustomerOptionsTable"), $values);
	}
	
	public function findByPrimaryKey($customer_id, $option_name){
		$this->findBy(array("customer_id" => $customer_id, "option_name" => $option_name));
	}
	
	public function findAllByCustomer($customer_id, $order = "", $reverse = false){
		return $this->findAllBy(array("customer_id" => $customer_id), $order, $reverse);
	}
	
	public function customer(){
		$loader = new Clay_Plugin("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($this->customer_id);
		return $customer;
	}
}
?>