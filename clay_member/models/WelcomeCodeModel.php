<?php
/**
 * 契約のモデルクラス
 */
class Member_WelcomeCodeModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Member");
		parent::__construct($loader->loadTable("WelcomeCodesTable"), $values);
	}
	
	public function findByPrimaryKey($welcome_code_id){
		$this->findBy(array("welcome_code_id" => $welcome_code_id));
	}
	
	public function findByDate($welcome_date){
		$this->findBy(array("welcome_date" => $welcome_date));
	}
	
	public function checkWelcome($code){
		$this->findBy(array("welcome_date" => date("Ymd"), "welcome_code" => $code));
	}
	
	function welcomes($order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$welcome = $loader->loadModel("WelcomeModel");
		return $this->findAllByDate($this->welcome_date, $order, $reverse);
	}
	
	function customer(){
		$loader = new PluginLoader("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($this->customer_id);
		return $customer;
	}
	
	function order(){
		$loader = new PluginLoader("Order");
		$order = $loader->loadModel("OrderModel");
		$order->findByPrimaryKey($this->order_id);
		return $order;
	}
	
	function getUrl($url){
		if(preg_match("/http:\\/\\//", $url) == 0){
			$url = "http://".$_SERVER["SERVER_NAME"].$url;
		}
		if(strpos($url, "?") > 0){
			return $url."&welcome_code=".$this->welcome_code;
		}
		return $url."?welcome_code=".$this->welcome_code;
	}
}
?>