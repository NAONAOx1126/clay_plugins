<?php
/**
 * Copyright (C) 2012 Clay System All Rights Reserved.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Clay System
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
	 * 役割のIDでオペレータのデータを検索する。
	 */
	public function findAllByRoleId($role_id){
		return $this->findAllBy(array("role_id" => $role_id));
	}
	
	/**
	 * 組織+役割のIDでオペレータのデータを検索する。
	 */
	public function findAllByCompanyRole($company_id, $role_id){
		return $this->findAllBy(array("company_id" => $company_id, "role_id" => $role_id));
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
	
	/**
	 * オペレータの所属する役割のデータを取得する。
	 */
	public function role(){
		$loader = new Clay_Plugin("Admin");
		$role = $loader->loadModel("RoleModel");
		$role->findByPrimaryKey($this->role_id);
		return $role;
		
	}
}
