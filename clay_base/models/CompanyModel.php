<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   3.0.0
 */

/**
 * 管理画面ユーザーの所属組織のモデルです。
 */
class Base_CompanyModel extends DatabaseModel{
	/**
	 * コンストラクタ
	 * @param $values モデルに初期設定する値
	 */
	public function __construct($values = array()){
		$loader = new PluginLoader();
		parent::__construct($loader->loadTable("CompanysTable"), $values);
	}
	
	/**
	 * 主キーでデータを取得する。
	 * @param $company_id 組織ID
	 */
	public function findByPrimaryKey($company_id){
		$this->findBy(array("company_id" => $company_id));
	}

	/**
	 * 組織に所属するオペレータのリストを取得する。
	 * @return オペレータのリスト
	 */
	public function operators(){
		$loader = new PluginLoader();
		$companyOperator = $loader->loadModel("CompanyOperatorModel");
		$companyOperators = $companyOperator->findAllByCompanyId($this->company_id);
		return $companyOperator;		
	}
	
	public function siteCompanys(){
		$loader = new PluginLoader();
		$siteCompany = $loader->loadModel("SiteCompanyModel");
		return $siteCompany->findAllByCompany($this->company_id);
		
	}
	
	public function hasSite($site_id){
		$loader = new PluginLoader();
		$siteCompany = $loader->loadModel("SiteCompanyModel");
		$siteCompany->findBySiteCompany($site_id, $this->company_id);
		if($siteCompany->site_id > 0){
			return true;
		}
		return false;
	}
	
	public function site($site_id){
		$loader = new PluginLoader();
		$siteCompany = $loader->loadModel("SiteCompanyModel");
		$siteCompany->findBySiteCompany($site_id, $this->company_id);
		return  $siteCompany->site();
	}
	
	public function sites(){
		$siteCompanys = $this->siteCompanys();
		$result = array();
		foreach($siteCompanys as $siteCompany){
			$result[] = $siteCompany->site();
		}
		return $result;		
	}
}
?>