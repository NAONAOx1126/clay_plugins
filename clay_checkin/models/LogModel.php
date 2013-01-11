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
class Checkin_LogModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Checkin");
		parent::__construct($loader->loadTable("LogsTable"), $values);
	}
	
	function findByPrimaryKey($log_id){
		$this->findBy(array("log_id" => $log_id));
	}
	
	function findAllByNotImported($type){
		return $this->findAllBy(array("type" => $type, "imported" => 0));
	}
	
	function findAllByImported($type){
		return $this->findAllBy(array("type" => $type, "imported" => 1));
	}
}
?>