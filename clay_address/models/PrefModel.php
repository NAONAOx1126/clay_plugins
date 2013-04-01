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
 * 都道府県のデータモデルです。。
 */
class Address_PrefModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 */
	function __construct($values = array()){
		$loader = new Clay_Plugin("address");
		parent::__construct($loader->loadTable("PrefsTable"), $values);
	}
	
	/**
	 * 主キーでデータを検索する。
	 */
	function findByPrimaryKey($pref_id){
		$this->findBy(array("id" => $pref_id));
	}
	
	/**
	 * 都道府県名でデータを検索する。
	 */
	function findByName($pref_name){
		$this->findBy(array("name" => $pref_name));
	}
	
	/**
	 * モデル自体を都道府県の名前文字列として扱えるようにする。
	 */
	function __toString(){
		return $this->name;
	}
}
