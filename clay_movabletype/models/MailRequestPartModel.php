<?php
/**
 * 顧客契約のモデルクラス
 */
class Movabletype_MailRequestPartModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Movabletype");
		parent::__construct($loader->loadTable("MailRequestPartsTable"), $values);
	}
	
	public function findByPrimaryKey($request_id, $entry_id){
		$this->findBy(array("request_id" => $request_id, "entry_id" => $entry_id));
	}
	
	public function findAllByRequest($request_id, $order = "", $reverse = false){
		return $this->findAllBy(array("request_id" => $request_id), $order, $reverse);
	}
	
	public function findAllByEntry($entry_id, $order = "", $reverse = false){
		return $this->findAllBy(array("entry_id" => $entry_id), $order, $reverse);
	}
	
	public function request(){
		$loader = new PluginLoader("MovableType");
		$request = $loader->loadModel("MailRequestModel");
		$request->findByPrimaryKey($this->request_id);
		return $request;
	}
	
	public function entry(){
		$loader = new PluginLoader("MovableType");
		$entry = $loader->loadModel("EntryModel");
		$entry->findByPrimaryKey($this->entry_id);
		return $entry;
	}
}
?>