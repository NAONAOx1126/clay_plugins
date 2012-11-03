<?php
/**
 * カテゴリ情報のモデルクラス
 */
class Product_ProductFlagModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Product");
		parent::__construct($loader->loadTable("ProductFlagsTable"), $values);
	}
	
	function findByPrimaryKey($product_id, $flag_id){
		$this->findBy(array("product_id" => $product_id, "flag_id" => $flag_id));
	}
	
	function findAllByProduct($product_id, $order = "", $reverse = false){
		return $this->findAllBy(array("product_id" => $product_id), $order, $reverse);
	}
	
	function findAllByFlag($flag_id, $order = "", $reverse = false){
		return $this->findAllBy(array("flag_id" => $flag_id), $order, $reverse);
	}
	
	function product(){
		$loader = new Clay_Plugin("Product");
		$product = $loader->loadModel("ProductModel");
		$product->findByPrimaryKey($this->product_id);
		return $product;
	}

	function flag(){
		$loader = new Clay_Plugin("Product");
		$flag = $loader->loadModel("FlagModel");
		$flag->findByPrimaryKey($this->flag_id);
		return $flag;
	}
}
?>