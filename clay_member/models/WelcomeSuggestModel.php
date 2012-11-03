<?php
/**
 * 契約のモデルクラス
 */
class Member_WelcomeSuggestModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Member");
		parent::__construct($loader->loadTable("WelcomeSuggestsTable"), $values);
	}
	
	public function findByPrimaryKey($suggest_id){
		$this->findBy(array("suggest_id" => $suggest_id));
	}
	
	public function findByWelcomeProduct($welcome_id, $product_id){
		$this->findBy(array("welcome_id" => $welcome_id, "product_id" => $product_id));
	}
	
	public function findAllByCustomer($customer_id, $order = "", $reverse = false){
		return $this->findAllBy(array("customer_id" => $customer_id), $order, $reverse);
	}

	public function findAllByWelcome($welcome_id, $order = "", $reverse = false){
		return $this->findAllBy(array("welcome_id" => $welcome_id), $order, $reverse);
	}

	public function findAllByProduct($product_id, $order = "", $reverse = false){
		return $this->findAllBy(array("product_id" => $product_id), $order, $reverse);
	}
	
	public function customer(){
		$loader = new Clay_Plugin("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($this->customer_id);
		return $customer;
	}

	public function welcome(){
		$loader = new Clay_Plugin("Member");
		$welcome = $loader->loadModel("WelcomeModel");
		$welcome->findByPrimaryKey($this->welcome_id);
		return $welcome;
	}

	public function product(){
		$loader = new Clay_Plugin("Product");
		$product = $loader->loadModel("ProductModel");
		$product->findByPrimaryKey($this->product_id);
		return $product;
	}
}
?>