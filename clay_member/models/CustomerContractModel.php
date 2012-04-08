<?php
/**
 * 顧客契約のモデルクラス
 */
class Member_CustomerContractModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Member");
		parent::__construct($loader->loadTable("CustomerContractsTable"), $values);
	}
	
	public function findByPrimaryKey($customer_id, $contract_id){
		$this->findBy(array("contract_id" => $contract_id));
	}
	
	public function findAllByCustomer($customer_id, $order = "", $reverse = false){
		return $this->findAllBy(array("customer_id" => $customer_id), $order, $reverse);
	}
	
	public function findAllByContract($contract_id, $order = "", $reverse = false){
		return $this->findAllBy(array("contract_id" => $contract_id), $order, $reverse);
	}
	
	public function customer(){
		$loader = new PluginLoader("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($this->customer_id);
		return $customer;
	}
	
	public function contract(){
		$loader = new PluginLoader("Member");
		$contract = $loader->loadModel("ContractModel");
		$contract->findByPrimaryKey($this->contract_id);
		return $contract;
	}
}
?>