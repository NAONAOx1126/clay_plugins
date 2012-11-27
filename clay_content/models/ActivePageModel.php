<?php
/**
 * アクティブページのモデルクラス
 */
class Content_ActivePageModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Content");
		parent::__construct($loader->loadTable("ActivePagesTable"), $values);
	}
	
	public function findByPrimaryKey($entry_id){
		$this->findBy(array("entry_id" => $entry_id));
	}

	public function findByProductId($product_id){
		$this->findBy(array("product_id" => $product_id));
	}

	public function findByProductCode($category1, $category2, $category3, $product_code){
		$this->findBy(array("category1" => $category1, "category2" => $category2, "category3" => $category3, "product_code" => $product_code));
	}
		
	public function findAllByShop($shop_id, $order = "", $reverse = false){
		return $this->findAllBy(array("shop_id" => $shop_id), $order, $reverse);
	}
	
	public function findAllByCategory1($category1, $order = "", $reverse = false){
		return $this->findAllBy(array("category1" => $category1), $order, $reverse);
	}
	
	public function findAllByCategory2($category1, $category2, $order = "", $reverse = false){
		return $this->findAllBy(array("category1" => $category1, "category2" => $category2), $order, $reverse);
	}
	
	public function findAllByCategory3($category1, $category2, $category3, $order = "", $reverse = false){
		return $this->findAllBy(array("category1" => $category1, "category2" => $category2, "category3" => $category3), $order, $reverse);
	}
	
	public function key(){
		$loader = new Clay_Plugin("Content");
		$activePageKey = $loader->loadModel("ActivePageKeyModel");
		$activePageKey->findByShop($this->shop_id);
		return $activePageKey;		
	}
}
?>