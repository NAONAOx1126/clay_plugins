<?php
/**
 * 二人の顧客を組み合わせるためのモデルクラス
 */
class Member_VwPairModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Member");
		parent::__construct($loader->loadTable("VwPairsTable"), $values);
	}
	
	public function findByPrimaryKey($pair_id){
		$this->findBy(array("pair_id" => $pair_id));
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
	 * ペアモデルを取得する
	*/
	public function entity(){
		$loader = new Clay_Plugin("Member");
		$pair = $loader->loadModel("PairModel");
		$pair->findByPrimaryKey($this->pair_id);
		return $pair;
	}
}
?>