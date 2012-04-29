<?php
/**
 * 顧客情報のモデルクラス
 */
class Product_ProductModel extends DatabaseModel{
	function __construct($values = array()){
		$loader = new PluginLoader("Product");
		parent::__construct($loader->loadTable("ProductsTable"), $values);
	}
	
	function findByPrimaryKey($product_id){
		$this->findBy(array("product_id" => $product_id));
	}
	
	function findByProductCode($product_code){
		$this->findBy(array("product_code" => $product_code));
	}
	
	function findAllByDeveloper($developer_id, $order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductModel");
		return $model->findAllBy(array("developer_id" => $developer_id), $order, $reverse);
	}
	
	function findAllBySeller($seller_id, $order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductModel");
		return $model->findAllBy(array("seller_id" => $seller_id), $order, $reverse);
	}
	
	function findAllByParent($parent_name, $order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductModel");
		return $model->findAllBy(array("parent_name" => $parent_name), $order, $reverse);
	}
	
	function isNew($days = 30){
		if(strtotime(date("Y-m-d 00:00:00", strtotime("-".$days." day"))) < strtotime($this->create_date)){
			return true;
		}
		return false;
	}
	
	function developer(){
		$loader = new PluginLoader("Product");
		$developer = $loader->loadModel("ProductDeveloperModel");
		$developer->findByPrimaryKey($this->developer_id);
		return $developer;
	}
	
	function seller(){
		$loader = new PluginLoader("Product");
		$seller = $loader->loadModel("ProductSellerModel");
		$seller->findByPrimaryKey($this->seller_id);
		return $seller;
	}
	
	function productCategories($order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductCategoryModel");
		return $model->findAllByProduct($this->product_id, $order, $reverse);
	}
	
	function hasCategory($category_id){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductCategoryModel");
		$model->findByPrimaryKey($this->product_id, $category_id);
		if($model->product_id > 0){
			return true;
		}
		return false;
	}
	
	function categories($values = array(), $order = "", $reverse = false){
		$productCategories = $this->productCategories();
		if(!is_array($values)){
			$values = array();
		}
		$values["in:category_id"] = array();
		foreach($productCategories as $item){
			$values["in:category_id"][] = $item->category_id;
		}
		$loader = new PluginLoader("Product");
		$product = $loader->loadModel("CategoryModel");
		return $product->findAllBy($values, $order, $reverse);
	}

	function category($type_id){
		$categories = $this->categories(array("category_type_id" => $type_id));
		if(count($categories) > 0){
			return $categories[0];
		}
		$loader = new PluginLoader("Product");
		return $loader->loadModel("CategoryModel");
	}

	function productFlags($order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductFlagModel");
		return $model->findAllByProduct($this->product_id, $order, $reverse);
	}
	
	function hasFlag($flag_id){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductFlagModel");
		$model->findByPrimaryKey($this->product_id, $flag_id);
		if($model->product_id > 0){
			return true;
		}
		return false;
	}
	
	function flags($values = array(), $order = "", $reverse = false){
		$productFlags = $this->productFlags();
		if(!is_array($values)){
			$values = array();
		}
		$values["in:flag_id"] = array();
		foreach($productFlags as $item){
			$values["in:flag_id"][] = $item->flag_id;
		}
		$loader = new PluginLoader("Product");
		$product = $loader->loadModel("FlagModel");
		return $product->findAllBy($values, $order, $reverse);
	}
	
	function images(){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductImageModel");
		$images = $model->findAllByProduct($this->product_id);
		$result = array();
		foreach($images as $image){
			$result[$image->image_type] = $image;
		}
		return $result;
	}

	function image($image_type){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductImageModel");
		$model->findByPrimaryKey($this->product_id, $image_type);
		return $model;
	}

	function productOptions($order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductOptionModel");
		return $model->findAllByProduct($this->product_id, $order, $reverse);
	}

	function productOption($option1_id = 0, $option2_id = 0, $option3_id = 0, $option4_id = 0){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductOptionModel");
		$model->findByPrimaryKey($this->product_id, $option1_id, $option2_id, $option3_id, $option4_id);
		return $model;
	}
}
?>