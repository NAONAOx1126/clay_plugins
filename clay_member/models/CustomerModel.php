<?php
/**
 * 顧客情報のモデルクラス
 */
class Member_CustomerModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Member");
		parent::__construct($loader->loadTable("CustomersTable"), $values);
	}
	
	public function findByPrimaryKey($customer_id){
		$this->findBy(array("customer_id" => $customer_id));
	}

	function findByCode($customer_code){
		$this->findBy(array("customer_code" => $customer_code));
	}
	
	function findByMobileId($mobile_id){
		$this->findBy(array("mobile_id" => $mobile_id));
	}
	
	function findByExternalId($external_id){
		$this->findBy(array("external_id" => $external_id));
	}
	
	function findByEmail($email){
		$this->findBy(array("email" => $email));
	}		
	
	function findByEmailMobile($email){
		$this->findBy(array("email_mobile" => $email));
	}
	
	/**
	 * 顧客契約のリストを取得する。
	*/
	public function customerContracts($order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$customerContract = $loader->loadModel("CustomerContractModel");
		return $customerContract->findAllByCustomer($this->customer_id, $order, $reverse);
	}

	function contracts($values = array(), $values = array(), $order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$customerContracts = $this->customerContracts();
		if(!is_array($values)){
			$values = array();
		}
		$values["in:contract_id"] = array();
		foreach($customerContracts as $item){
			$values["in:contract_id"][] = $item->contract_id;
		}
		$contract = $loader->loadModel("ContractModel");
		return $contract->findAllBy($values, $order, $reverse);
	}
	
	/**
	 * 顧客オプションのリストを取得する。
	*/
	public function customerOptions($order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$customerOption = $loader->loadModel("CustomerOptionModel");
		return $customerOption->findAllByCustomer($this->customer_id, $order, $reverse);
	}
	
	public function customerOption($option_name){
		$loader = new PluginLoader("Member");
		$customerOption = $loader->loadModel("CustomerOptionModel");
		$customerOption->findByPrimaryKey($this->customer_id, $option_name);
		return $customerOption;
	}

	public function customerDelivers($order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$customerDeliver = $loader->loadModel("CustomerDeliverModel");
		return $customerDeliver->findAllByCustomer($this->customer_id, $order, $reverse);
	}
	
	public function pointLogs($order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$pointLog = $loader->loadModel("PointLogModel");
		return $pointLog->findAllByCustomer($this->customer_id, $order, $reverse);
	}
	
	public function serialLogs($order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$serialLog = $loader->loadModel("SerialLogModel");
		return $serialLog->findAllByCustomer($this->customer_id, $order, $reverse);
	}

	public function welcomeSuggests($order = "", $reverse = false){
		$loader = new PluginLoader("Member");
		$welcomeSuggest = $loader->loadModel("WelcomeSuggestModel");
		return $welcomeSuggest->findAllByCustomer($this->customer_id, $order, $reverse);
	}
	
	/**
	 * 都道府県の名前を取得
	 */
	 function pref_name($pref_name = null){
		$loader = new PluginLoader();
		$pref = $loader->loadModel("PrefModel");
		// 引数を渡した場合はIDを登録
		if($pref_name != null){
			$pref->findByName($pref_name);
			$this->pref = $pref->id;
		}
		$pref->findByPrimaryKey($this->pref);
		return $pref->name;
	 }
}
?>