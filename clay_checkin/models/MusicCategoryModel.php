<?php
/**
 * チェックインの楽曲カテゴリ情報を扱うモデルです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Models
 * @package   Checkin
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Checkin_MusicCategoryModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Checkin");
		parent::__construct($loader->loadTable("MusicCategoriesTable"), $values);
	}
	
	function findByPrimaryKey($category_id){
		$this->findBy(array("category_id" => $category_id));
	}
	
	function findByName($category_name){
		$this->findBy(array("category_name" => $category_name));
	}
	
	function logs($order = "", $reverse = false){
		// 楽曲カテゴリプラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		$musicLog = $loader->loadModel("CustomerMusicLogModel");
		$musicLog->findAllByCategory($this->category_id, $order = "", $reverse = false);
		return $musicLog;
	}

	function logCount(){
		// 楽曲カテゴリプラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		$musicLog = $loader->loadModel("CustomerMusicLogModel");
		return $musicLog->countBy(array("category_id" => $this->category_id));
	}
}
?>