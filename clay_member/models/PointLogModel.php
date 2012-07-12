<?php
/**
 * ポイントログのモデルクラス
 */
class Member_PointLogModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Member");
		parent::__construct($loader->loadTable("PointLogsTable"), $values);
	}
	
	public function findByPrimaryKey($point_log_id){
		$this->findBy(array("point_log_id" => $point_log_id));
	}
	
	public function findAllByCustomer($customer_id, $order = "", $reverse = false){
		return $this->findAllBy(array("customer_id" => $customer_id), $order, $reverse);
	}
	
	public function customer(){
		$loader = new PluginLoader("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($this->customer_id);
		return $customer;
	}
	
	function add($point, $comment = "", $commit = true){
		$this->log_time = date("Y-m-d H:i:s");
		$this->customer_id = ($_SESSION[CUSTOMER_SESSION_KEY]["customer_id"] > 0)?$_SESSION[CUSTOMER_SESSION_KEY]["customer_id"]:$_POST["customer_id"];
		$this->point = $point;
		$this->comment = $comment;
		if($commit){
			$this->commit_flg = 1;
		}else{
			$this->commit_flg = 0;
		}
		if($this->customer_id > 0 && $this->point != 0){
			parent::save();
		}
	}
}
?>