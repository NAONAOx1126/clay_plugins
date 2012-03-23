<?php
/**
 * カテゴリ情報のモデルクラス
 */
class Product_ProductImageModel extends DatabaseModel{
	function __construct($values = array()){
		$loader = new PluginLoader("Product");
		parent::__construct($loader->loadTable("ProductImagesTable"), $values);
	}
	
	function findByPrimaryKey($product_id, $image_type){
		$this->findBy(array("product_id" => $product_id, "image_type" => $image_type));
	}
	
	function findAllByProduct($product_id, $order = "", $reverse = false){
		return $this->findAllBy(array("product_id" => $product_id), $order, $reverse);
	}
	
	function product(){
		$loader = new PluginLoader("Product");
		$product = $loader->loadModel("ProductModel");
		$product->findByPrimaryKey($this->product_id);
		return $product;
	}
}
?>