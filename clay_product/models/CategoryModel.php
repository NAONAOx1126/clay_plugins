<?php
/**
 * 商品カテゴリのデータモデルです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Extensions
 * @package   Base
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */

/**
 * 決済方法のモデルクラス
 */
class Product_CategoryModel extends DatabaseModel{
	function __construct($values = array()){
		$loader = new PluginLoader("Product");
		parent::__construct($loader->loadTable("CategoriesTable"), $values);
	}
	
	function findByPrimaryKey($category_id){
		$this->findBy(array("category_id" => $category_id));
	}

	function findAllByGroup($category_group_id, $order = "", $reverse = false){
		return $this->findAllBy(array("category_group_id" => $category_group_id), $order, $reverse);
	}
	
	function findAllByType($category_type_id, $order = "", $reverse = false){
		return $this->findAllBy(array("category_type_id" => $category_type_id), $order, $reverse);
	}
	
	function productCategories($order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$productCategory = $loader->loadModel("ProductCategoryModel");
		return $productCategory->findAllByCategory($this->category_id, $order, $reverse);
	}
	
	function productMultiCategories($other, $order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$productCategory = $loader->loadModel("ProductCategoryModel");
		$in_products = $productCategory->findAllByCategory($other, $order, $reverse);
		$conditions = array("category_id" => $this->category_id);
		if(is_array($in_products) && count($in_products) > 0){
			$conditions["in:product_id"] = array();
			foreach($in_products as $in_product){
				$conditions["in:product_id"][] = $in_product->product_id;
			}
		}
		return $productCategory->findAllBy($conditions, $order, $reverse);
	}
	
	function products($values = array(), $order = "", $reverse = false){
		$productCategories = $this->productCategories();
		if(!is_array($values)){
			$values = array();
		}
		$values["in:product_id"] = array();
		foreach($productCategories as $item){
			$values["in:product_id"][] = $item->product_id;
		}
		$product = $loader->loadModel("ProductModel");
		return $product->findAllBy($values, $order, $reverse);
	}
	
	function type(){
		$type = $loader->loadModel("CateogryTypeModel");
		$type->findByPrimaryKey($this->category_type_id);
		return $type;
	}
}
?>