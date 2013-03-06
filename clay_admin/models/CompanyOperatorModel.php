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
 * 管理画面ユーザーのモデルです。
 */
class Admin_CompanyOperatorModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 */
	public function __construct($values = array()){
		$loader = new Clay_Plugin("admin");
		parent::__construct($loader->loadTable("CompanyOperatorsTable"), $values);
	}
	
	/**
	 * 主キーでオペレータを検索する。
	 */
	public function findByPrimaryKey($operator_id){
		$this->findBy(array("operator_id" => $operator_id));
	}

	/**
	 * オペレータのログインIDでデータを検索する。
	 */
	public function findByLoginId($login_id){
		$this->findBy(array("login_id" => $login_id));
	}
	
	/**
	 * 組織のIDでオペレータのデータを検索する。
	 */
	public function findAllByCompanyId($company_id){
		return $this->findAllBy(array("company_id" => $company_id));
	}
	
	/**
	 * 特権オペレータかどうかを調べる。
	 */
	public function isSuper(){
		return $this->super_flg;
	}
	
	/**
	 * オペレータの所属する組織のデータを取得する。
	 */
	public function company(){
		$loader = new Clay_Plugin("Admin");
		$company = $loader->loadModel("CompanyModel");
		$company->findByPrimaryKey($this->company_id);
		return $company;
		
	}
}
