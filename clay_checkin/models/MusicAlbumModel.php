<?php
/**
 * チェックインの楽曲アルバム情報を扱うモデルです。
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
class Checkin_MusicAlbumModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Checkin");
		parent::__construct($loader->loadTable("MusicAlbumsTable"), $values);
	}
	
	function findByPrimaryKey($album_id){
		$this->findBy(array("album_id" => $album_id));
	}
	
	function findByName($album_name, $disk_no, $track_no){
		$this->findBy(array("album_name" => $album_name, "disk_no" => $disk_no, "track_no" => $track_no));
	}
	
	function logs($order = "", $reverse = false){
		// 楽曲カテゴリプラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		$musicLog = $loader->loadModel("CustomerMusicLogModel");
		$musicLog->findAllByAlbum($this->album_id, $order = "", $reverse = false);
		return $musicLog;
	}

	function logCount(){
		// 楽曲カテゴリプラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		$musicLog = $loader->loadModel("CustomerMusicLogModel");
		return $musicLog->countBy(array("album_id" => $this->album_id));
	}
}
?>