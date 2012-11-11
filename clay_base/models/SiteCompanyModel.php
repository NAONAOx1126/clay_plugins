<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */
 
/**
 * サイトと所属組織の対応関係のモデルです。
 */
class Base_SiteCompanyModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 * @param $values モデルに初期設定する値
	 */
	public function __construct($values = array()){
		$loader = new Clay_Plugin();
		parent::__construct($loader->loadTable("SiteCompanysTable"), $values);
	}
	
	/**
	 * 主キーでデータを取得する。
	 * @param $company_id 組織ID
	 */
	public function findByPrimaryKey($site_company_id){
		$this->findBy(array("site_company_id" => $site_company_id));
	}
	
	public function findBySiteCompany($site_id, $company_id){
		$this->findBy(array("site_id" => $site_id, "company_id" => $company_id));
	}
	
	public function findAllBySite($site_id){
		return $this->findAllBy(array("site_id" => $site_id));
	}

	public function findAllByCompany($company_id){
		return $this->findAllBy(array("company_id" => $company_id));
	}
	
	public function site(){
		$loader = new Clay_Plugin();
		$site = $loader->loadModel("SiteModel");
		$site->findByPrimaryKey($this->site_id);
		return $site;
	}

	public function company(){
		$loader = new Clay_Plugin();
		$company = $loader->loadModel("CompanyModel");
		$company->findByPrimaryKey($this->company_id);
		return $company;
	}
}
