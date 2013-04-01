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
 * 管理画面ユーザーの所属組織のモデルです。
 */
class Admin_CompanyModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 * @param $values モデルに初期設定する値
	 */
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Admin");
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
		$loader = new Clay_Plugin("admin");
		$companyOperator = $loader->loadModel("CompanyOperatorModel");
		$companyOperators = $companyOperator->findAllByCompanyId($this->company_id);
		return $companyOperators;		
	}
	
	/**
	 * 組織に所属するオペレータを取得する。
	 * @return オペレータ
	 */
	public function operator(){
		$loader = new Clay_Plugin("admin");
		$companyOperator = $loader->loadModel("CompanyOperatorModel");
		$companyOperators = $companyOperator->findAllByCompanyId($this->company_id);
		if(count($companyOperators) > 0){
			return $companyOperators[0];
		}else{
			return $companyOperator;
		}
	}
	
	/**
	 * 組織で所有するFacebookGroupのリストを取得する。
	 * @return オペレータのリスト
	 */
	public function facebook_groups(){
		$loader = new Clay_Plugin("Facebook");
		$group = $loader->loadModel("GroupModel");
		$groups = $group->findAllByCompany($this->company_id);
		return $groups;		
	}
	
	/**
	 * 都道府県の名前を取得
	 */
	 function pref_name($pref_name = null){
		$loader = new Clay_Plugin("Address");
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
