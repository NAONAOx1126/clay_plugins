<?php
/**
 * 契約のモデルクラス
 */
class Movabletype_CategoryModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Movabletype");
		parent::__construct($loader->loadTable("CategoriesTable"), $values);
	}
	
	public function findByPrimaryKey($category_id){
		$this->findBy(array("category_id" => $category_id));
	}
	
	public function placements($order = "", $reverse = false){
		$loader = new PluginLoader("Movabletype");
		$placement = $loader->loadModel("PlacementModel");
		return $placement->findAllByCategory($this->category_id, $order, $reverse);
	}
}
?>