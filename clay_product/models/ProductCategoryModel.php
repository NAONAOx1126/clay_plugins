<?php
/**
 * カテゴリ情報のモデルクラス
 */
class Product_ProductCategoryModel extends DatabaseModel{
	function __construct($values = array()){
		$loader = new PluginLoader("Product");
		parent::__construct($loader->loadTable("ProductCategoriesTable"), $values);
	}
	
	function findByPrimaryKey($product_id, $category_id){
		$this->findBy(array("product_id" => $product_id, "category_id" => $category_id));
	}
	
	function findAllByProduct($product_id, $order = "", $reverse = false){
		return $this->findAllBy(array("product_id" => $product_id), $order, $reverse);
	}
	
	function findAllByCategory($category_id, $order = "", $reverse = false){
		return $this->findAllBy(array("category_id" => $category_id), $order, $reverse);
	}
	
	function product(){
		$loader = new PluginLoader("Product");
		$product = $loader->loadModel("ProductModel");
		$product->findByPrimaryKey($this->product_id);
		return $product;
	}

	function category(){
		$loader = new PluginLoader("Product");
		$category = $loader->loadModel("CategoryModel");
		$category->findByPrimaryKey($this->category_id);
		return $category;
	}
}
?>