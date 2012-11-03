<?php
/**
 * 契約のモデルクラス
 */
class Movabletype_CategoryModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Movabletype");
		parent::__construct($loader->loadTable("CategoriesTable"), $values);
	}
	
	public function findByPrimaryKey($category_id){
		$this->findBy(array("category_id" => $category_id));
	}
	
	public function placements($order = "", $reverse = false){
		$loader = new Clay_Plugin("Movabletype");
		$placement = $loader->loadModel("PlacementModel");
		return $placement->findAllByCategory($this->category_id, $order, $reverse);
	}
}
?>