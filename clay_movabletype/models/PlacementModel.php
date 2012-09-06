<?php
/**
 * 契約のモデルクラス
 */
class Movabletype_PlacementModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Movabletype");
		parent::__construct($loader->loadTable("PlacementsTable"), $values);
	}
	
	public function findByPrimaryKey($placement_id){
		$this->findBy(array("placement_id" => $placement_id));
	}
	
	public function findPrimaryByEntry($entry_id){
		$this->findBy(array("placement_entry_id" => $entry_id, "placement_is_primary" => "1"));
	}
	
	public function findAllByEntry($entry_id, $order = "", $reverse = false){
		return $this->findAllBy(array("placement_entry_id" => $entry_id), $order, $reverse);
	}
	
	public function findSecondaryAllByEntry($entry_id, $order = "", $reverse = false){
		return $this->findAllBy(array("placement_entry_id" => $entry_id, "placement_is_primary" => "0"), $order, $reverse);
	}
	
	public function findAllByCategory($category_id, $order = "", $reverse = false){
		return $this->findAllBy(array("placement_category_id" => $category_id), $order, $reverse);
	}
	
	function entry(){
		$loader = new PluginLoader("Movabletype");
		$entry = $loader->loadModel("EntryModel");
		$entry->findByPrimaryKey($this->placement_entry_id);
		return $entry;
	}

	function category(){
		$loader = new PluginLoader("Movabletype");
		$category = $loader->loadModel("CategoryModel");
		$category->findByPrimaryKey($this->placement_category_id);
		return $category;
	}
}
?>