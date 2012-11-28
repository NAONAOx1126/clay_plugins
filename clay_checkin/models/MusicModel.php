<?php
/**
 * チェックインの楽曲情報を扱うモデルです。
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
class Checkin_MusicModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Checkin");
		parent::__construct($loader->loadTable("MusicsTable"), $values);
	}
	
	function findByPrimaryKey($music_id){
		$this->findBy(array("music_id" => $music_id));
	}
	
	function findByName($artist_name, $music_name){
		$this->findBy(array("artist_name" => $artist_name, "music_name" => $music_name));
	}
	
	function logs($order = "", $reverse = false){
		// 楽曲カテゴリプラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		$musicLog = $loader->loadModel("CustomerMusicLogModel");
		$musicLog->findAllByMusic($this->music_id, $order = "", $reverse = false);
		return $musicLog;
	}

	function logCount(){
		// 楽曲カテゴリプラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		$musicLog = $loader->loadModel("CustomerMusicLogModel");
		return $musicLog->countBy(array("music_id" => $this->music_id));
	}
}
?>