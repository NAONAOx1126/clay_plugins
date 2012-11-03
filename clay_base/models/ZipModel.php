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
 * 郵便番号のデータモデルです。。
 */
class Base_ZipModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 */
	function __construct($values = array()){
		$loader = new Clay_Plugin();
		parent::__construct($loader->loadTable("ZipsTable"), $values);
	}
	
	/**
	 * 主キーでデータを検索する。
	 */
	function findByCode($code){
		$this->findBy(array("code" => $code));
		if($this->town == "以下に掲載がない場合"){
			$this->town = "";
			$this->town_kana = "";
		}
	}
	
	/**
	 * モデル自体を漢字住所の文字列として扱えるようにする。
	 */
	function __toString(){
		return $this->state.$this->city.$this->town;
	}
}
?>