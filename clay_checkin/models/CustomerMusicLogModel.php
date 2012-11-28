<?php
/**
 * チェックインの楽曲再生ログ情報を扱うモデルです。
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
class Checkin_CustomerMusicLogModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Checkin");
		parent::__construct($loader->loadTable("CustomerMusicLogsTable"), $values);
	}
	
	function findByPrimaryKey($music_log_id){
		$this->findBy(array("music_log_id" => $music_log_id));
	}
	
	function findAllByCategory($category_id, $order = "", $reverse = false){
		return $this->findAllBy(array("category_id" => $category_id), $order, $reverse);
	}
	
	function findAllByAlbum($album_id, $order = "", $reverse = false){
		return $this->findAllBy(array("album_id" => $album_id), $order, $reverse);
	}
	
	function findAllByMusic($music_id, $order = "", $reverse = false){
		return $this->findAllBy(array("music_id" => $music_id), $order, $reverse);
	}
	
	function category(){
		// 楽曲カテゴリプラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		
		$category = $loader->loadModel("MusicCategoryModel");
		$category->findByPrimaryKey($this->category_id);
		return $category;
	}

	
	function album(){
		// 楽曲アルバムプラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		
		$album = $loader->loadModel("MusicAlbumModel");
		$album->findByPrimaryKey($this->album_id);
		return $album;
	}

	
	function music(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		
		$music = $loader->loadModel("MusicModel");
		$music->findByPrimaryKey($this->music_id);
		return $music;
	}
}
?>