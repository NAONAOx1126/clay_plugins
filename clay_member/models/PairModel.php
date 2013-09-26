<?php
/**
 * 二人の顧客を組み合わせるためのモデルクラス
 */
class Member_PairModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Member");
		parent::__construct($loader->loadTable("PairsTable"), $values);
	}
	
	public function findByPrimaryKey($pair_id){
		$this->findBy(array("pair_id" => $pair_id));
	}

	function findAllByCompanyId($company_id, $order = "", $reverse = false){
		return $this->findAllBy(array("company_id" => $company_id), $order, $reverse);
	}
	
	function splitedPairCode($index){
		if($this->pair_code != ""){
			$splited = explode("-", $this->pair_code);
			return $splited[$index];
		}
		return "";
	}
	
	/**
	 * 所属組織のリストを取得する。
	*/
	public function company(){
		$loader = new Clay_Plugin("Admin");
		$company = $loader->loadModel("CompanyModel");
		$company->findByPrimaryKey($this->company_id);
		return $company;
	}
	
	/**
	 * 顧客のリストを取得する。
	*/
	public function customer1(){
		$loader = new Clay_Plugin("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findBy(array("pair_id" => $this->pair_id, "sex" => "1"));
		if(!($customer->customer_id > 0)){
			$customer->findBy(array("pair_id" => $this->pair_id, "sex" => "2"));
		}
		return $customer;
	}
	
	public function customer2($order = "", $reverse = false){
		$loader = new Clay_Plugin("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findBy(array("pair_id" => $this->pair_id, "sex" => "1"));
		if($customer->customer_id > 0){
			$customer = $loader->loadModel("CustomerModel");
			$customer->findBy(array("pair_id" => $this->pair_id, "sex" => "2"));
		}else{
			$customer = $loader->loadModel("CustomerModel");
		}
		return $customer;
	}
}
?>