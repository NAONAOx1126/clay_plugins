<?php
/**
 * 顧客情報のモデルクラス
 */
class Product_ProductOptionModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Product");
		parent::__construct($loader->loadTable("ProductOptionsTable"), $values);
	}
	
	function findByPrimaryKey($product_id, $option1_id = 0, $option2_id = 0, $option3_id = 0, $option4_id = 0){
		$condition = array();
		$condition["product_id"] = $product_id;
		$condition["option1_id"] = $option1_id;
		$condition["option2_id"] = $option2_id;
		$condition["option3_id"] = $option3_id;
		$condition["option4_id"] = $option4_id;
		$this->findBy($condition);
	}
	
	function findAllByProduct($product_id, $order = "", $reverse = false){
		return $this->findAllBy(array("product_id" => $product_id), $order, $reverse);
	}
	
	function product(){
		$loader = new Clay_Plugin("Product");
		$product = $loader->loadModel("ProductModel");
		$product->findByPrimaryKey($this->product_id);
		return $product;
	}
}
?>