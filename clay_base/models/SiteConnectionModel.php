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
 * サイトDB接続情報のデータモデルです。
 */
class Base_SiteConnectionModel extends DatabaseModel{
	/**
	 * コンストラクタ
	 */
	function __construct($values = array()){
		$loader = new PluginLoader();
		parent::__construct($loader->loadTable("SiteConnectionsTable"), $values);
	}
	
	/**
	 * 主キーで検索する。
	 */
	function findByPrimaryKey($site_id, $connection_code){
		$this->findBy(array("site_id" => $site_id, "connection_code" => $connection_code));
	}
	
	/**
	 * サイトIDで検索する。
	 */
	function findAllBySiteId($site_id){
		return $this->findAllBy(array("site_id" => $site_id));
	}
}
?>