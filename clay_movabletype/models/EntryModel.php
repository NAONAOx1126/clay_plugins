<?php
/**
 * 契約のモデルクラス
 */
class Movabletype_EntryModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Movabletype");
		parent::__construct($loader->loadTable("EntriesTable"), $values);
	}
	
	public function findByPrimaryKey($entry_id){
		$this->findBy(array("entry_id" => $entry_id));
	}
	
	public function requestParts($order = "", $reverse = false){
		$loader = new Clay_Plugin("Movabletype");
		$requestPart = $loader->loadModel("MailRequestPartModel");
		return $requestPart->findAllByRequest($this->entry_id, $order, $reverse);
	}
	
	public function metaInfo($entry_meta_type){
		$loader = new Clay_Plugin("Movabletype");
		$entryMeta = $loader->loadModel("EntryMetaModel");
		$entryMeta->findByPrimaryKey($this->entry_id, $entry_meta_type);
		return $entryMeta;
	}

	public function primaryPlacement(){
		$loader = new Clay_Plugin("Movabletype");
		$placement = $loader->loadModel("PlacementModel");
		$placement->findPrimaryByEntry($this->entry_id);
		return $placement;
	}
	
	public function secondaryPlacements($order = "", $reverse = false){
		$loader = new Clay_Plugin("Movabletype");
		$placement = $loader->loadModel("PlacementModel");
		return $placement->findSecondaryAllByEntry($this->entry_id, $order, $reverse);
	}
	
	public function placements($order = "", $reverse = false){
		$loader = new Clay_Plugin("Movabletype");
		$placement = $loader->loadModel("PlacementModel");
		return $placement->findAllByEntry($this->entry_id, $order, $reverse);
	}
}
?>