<?php
/**
 * 契約のモデルクラス
 */
class Movabletype_EntryMetaModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Movabletype");
		parent::__construct($loader->loadTable("EntryMetasTable"), $values);
	}
	
	public function findByPrimaryKey($entry_id, $entry_meta_type){
		$this->findBy(array("entry_meta_entry_id" => $entry_id, "entry_meta_type" => $entry_meta_type));
	}
	
	public function findAllByEntry($entry_id, $order = "", $reverse = false){
		return $this->findAllBy(array("entry_meta_entry_id" => $entry_id), $order, $reverse);
	}
	
	function entry(){
		$loader = new PluginLoader("Movabletype");
		$entry = $loader->loadModel("EntryModel");
		$entry->findByPrimaryKey($this->entry_meta_entry_id);
		return $entry;
	}
}
?>