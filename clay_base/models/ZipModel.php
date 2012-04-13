<?php
/**
 * 郵便番号のデータモデルです。。
 *
 * @category  Model
 * @package   Base
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   1.0.0
 */
class Base_ZipModel extends DatabaseModel{
	/**
	 * コンストラクタ
	 */
	function __construct($values = array()){
		$loader = new PluginLoader();
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