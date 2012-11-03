<?php
/**
 * 顧客配送先のモデルクラス
 */
class Member_CustomerDeliverModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Member");
		parent::__construct($loader->loadTable("CustomerDeliversTable"), $values);
	}
	
	public function findByPrimaryKey($customer_deliver_id){
		$this->findBy(array("customer_deliver_id" => $customer_deliver_id));
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