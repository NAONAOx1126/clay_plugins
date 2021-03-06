<?php
/**
 * 契約のモデルクラス
 */
class Member_WelcomeModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Member");
		parent::__construct($loader->loadTable("WelcomesTable"), $values);
	}
	
	protected function appendWhere($select, $key, $value){
		if(preg_match("/^(.+:)?welcome_date$/", $key) > 0){
			$value = str_replace("-", "", $value);
		}
		return parent::appendWhere($select, $key, $value);
	}
	
	public function findByPrimaryKey($welcome_id){
		$this->findBy(array("welcome_id" => $welcome_id));
	}
	
	public function findByWelcomeCustomer($welcome_date, $customer_id){
		$this->findBy(array("welcome_date" => $welcome_date, "customer_id" => $customer_id));
	}
	
	public function findAllByDate($welcome_date, $order = "", $reverse = false){
		return $this->findAllBy(array("welcome_date" => $welcome_date), $order, $reverse);
	}
	
	function code(){
		$loader = new Clay_Plugin("Member");
		$code = $loader->loadModel("WelcomeCodeModel");
		$code->findByDate($this->welcome_date);
		return $code;
	}
	
	function customer(){
		$loader = new Clay_Plugin("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($this->customer_id);
		return $customer;
	}
	
	function order(){
		$loader = new Clay_Plugin("Order");
		$order = $loader->loadModel("OrderModel");
		$order->findByPrimaryKey($this->order_id);
		return $order;
	}
	
	public function welcomeSuggests($order = "", $reverse = false){
		$loader = new Clay_Plugin("Member");
		$welcomeSuggest = $loader->loadModel("WelcomeSuggestModel");
		return $welcomeSuggest->findAllByWelcome($this->welcome_id, $order, $reverse);
	}
}
?>