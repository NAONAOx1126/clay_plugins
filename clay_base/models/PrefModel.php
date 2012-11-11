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
 * 都道府県のデータモデルです。。
 */
class Base_PrefModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 */
	function __construct($values = array()){
		$loader = new Clay_Plugin();
		parent::__construct($loader->loadTable("PrefsTable"), $values);
	}
	
	/**
	 * 主キーでデータを検索する。
	 */
	function findByPrimaryKey($pref_id){
		$this->findBy(array("id" => $pref_id));
	}
	
	/**
	 * 主キーでデータを検索する。
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
