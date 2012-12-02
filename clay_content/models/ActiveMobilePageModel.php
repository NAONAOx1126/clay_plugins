<?php
/**
 * アクティブページのモデルクラス
 */
class Content_ActiveMobilePageModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Content");
		parent::__construct($loader->loadTable("ActiveMobilePagesTable"), $values);
	}
	
	public function findByPrimaryKey($entry_id){
		$this->findBy(array("entry_id" => $entry_id));
	}

	public function findByProductId($product_id){
		$this->findBy(array("product_id" => $product_id));
	}

	public function findByProductCode($category1, $category2, $category3, $product_code){
		$keys = array();
		if(!empty($category1)){
			$keys["category1"] = $category1;
		}
		if(!empty($category2)){
			$keys["category2"] = $category2;
		}
		if(!empty($category3)){
			$keys["category3"] = $category3;
		}
		if(!empty($product_code)){
			$keys["product_code"] = $product_code;
		}
		$this->findBy($keys);
	}
		
	public function findAllByShop($shop_id, $order = "", $reverse = false){
		return $this->findAllBy(array("shop_id" => $shop_id), $order, $reverse);
	}
	
	public function findAllByCategory1($category1, $order = "", $reverse = false){
		return $this->findAllBy(array("category1" => $category1), $order, $reverse);
	}
	
	public function findAllByCategory2($category1, $category2, $order = "", $reverse = false){
		$result = $this->findAllBy(array("category1" => $category1, "category2" => $category2), $order, $reverse);
		print_r($result);
		exit;
		return $result;
	}
	
	public function findAllByCategory3($category1, $category2, $category3, $order = "", $reverse = false){
		return $this->findAllBy(array("category1" => $category1, "category2" => $category2, "category3" => $category3), $order, $reverse);
	}
}
?>