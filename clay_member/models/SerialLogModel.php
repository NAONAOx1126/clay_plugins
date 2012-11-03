<?php
/**
 * シリアル発行ログのモデルクラス
 */
class Member_SerialLogModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Member");
		parent::__construct($loader->loadTable("SerialLogsTable"), $values);
	}
	
	public function findByPrimaryKey($serial_log_id){
		$this->findBy(array("serial_log_id" => $serial_log_id));
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