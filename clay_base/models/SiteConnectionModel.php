<?php
/**
 * サイトDB接続情報のデータモデルです。
 *
 * @category  Model
 * @package   Base
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   1.0.0
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