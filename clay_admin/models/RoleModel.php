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
 * 管理画面ユーザーの役割のモデルです。
 */
class Admin_RoleModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 * @param $values モデルに初期設定する値
	 */
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Admin");
		parent::__construct($loader->loadTable("RolesTable"), $values);
	}
	
	/**
	 * 主キーでデータを取得する。
	 * @param $role_id 役割ID
	 */
	public function findByPrimaryKey($role_id){
		$this->findBy(array("role_id" => $role_id));
	}
	
	/**
	 * 役割コードでデータを取得する。
	 * @param $role_code 役割コード
	 */
	public function findByCode($role_code){
		$this->findBy(array("role_code" => $role_code));
	}

	/**
	 * この役割を持つオペレータのリストを取得する。
	 * @return オペレータのリスト
	 */
	public function operators(){
		$loader = new Clay_Plugin("admin");
		$companyOperator = $loader->loadModel("CompanyOperatorModel");
		$companyOperators = $companyOperator->findAllByRoleId($this->role_id);
		return $companyOperators;		
	}
}
