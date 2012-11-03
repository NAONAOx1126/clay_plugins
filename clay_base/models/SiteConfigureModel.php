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
 * サイト各種設定情報のデータモデルです。
 */
class Base_SiteConfigureModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 */
	function __construct($values = array()){
		$loader = new Clay_Plugin();
		parent::__construct($loader->loadTable("SiteConfiguresTable"), $values);
	}
	
	/**
	 * 主キーで検索する。
	 */
	function findByPrimaryKey($site_id, $name){
		$this->findBy(array("site_id" => $site_id, "name" => $name));
	}
	
	/**
	 * サイトIDで検索する。
	 */
	function findAllBySiteId($site_id){
		return $this->findAllBy(array("site_id" => $site_id));
	}
}
?>