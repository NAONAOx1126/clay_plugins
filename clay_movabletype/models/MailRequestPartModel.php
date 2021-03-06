<?php
/**
 * 顧客契約のモデルクラス
 */
class Movabletype_MailRequestPartModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Movabletype");
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
		$loader = new Clay_Plugin("MovableType");
		$request = $loader->loadModel("MailRequestModel");
		$request->findByPrimaryKey($this->request_id);
		return $request;
	}
	
	public function entry(){
		$loader = new Clay_Plugin("MovableType");
		$entry = $loader->loadModel("EntryModel");
		$entry->findByPrimaryKey($this->entry_id);
		return $entry;
	}
}
?>