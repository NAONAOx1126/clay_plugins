<?php
/**
 * 契約のモデルクラス
 */
class Member_ContractModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Member");
		parent::__construct($loader->loadTable("ContractsTable"), $values);
	}
	
	public function findByPrimaryKey($contract_id){
		$this->findBy(array("contract_id" => $contract_id));
	}
	
	public function customerContracts($order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$customerContract = $loader->loadModel("CustomerContractModel");
		return $customerContract->findAllByContract($this->contract_id, $order, $reverse);
	}
	
	function customers($values = array(), $order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$customerContracts = $this->customerContracts();
		if(!is_array($values)){
			$values = array();
		}
		$values["in:customer_id"] = array();
		foreach($customerContracts as $item){
			$values["in:customer_id"][] = $item->customer_id;
		}
		$customer = $loader->loadModel("CustomerModel");
		return $customer->findAllBy($values, $order, $reverse);
	}
	
	function product(){
		$loader = new PluginLoader("Product");
		$product = $loader->loadModel("ProductModel");
		$product->findByPrimaryKey($this->contract_product_id);
		return $product;
	}
}
?>