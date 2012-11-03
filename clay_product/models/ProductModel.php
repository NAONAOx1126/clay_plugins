<?php
/**
 * 顧客情報のモデルクラス
 */
class Product_ProductModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Product");
		parent::__construct($loader->loadTable("ProductsTable"), $values);
	}
	
	function findByPrimaryKey($product_id){
		$this->findBy(array("product_id" => $product_id));
	}
	
	function findByProductCode($product_code){
		$this->findBy(array("product_code" => $product_code));
	}
	
	function findAllByDeveloper($developer_id, $order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductModel");
		return $model->findAllBy(array("developer_id" => $developer_id), $order, $reverse);
	}
	
	function findAllBySeller($seller_id, $order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductModel");
		return $model->findAllBy(array("seller_id" => $seller_id), $order, $reverse);
	}
	
	function findAllByParent($parent_name, $order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
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
		$loader = new Clay_Plugin("Product");
		$developer = $loader->loadModel("ProductDeveloperModel");
		$developer->findByPrimaryKey($this->developer_id);
		return $developer;
	}
	
	function seller(){
		$loader = new Clay_Plugin("Product");
		$seller = $loader->loadModel("ProductSellerModel");
		$seller->findByPrimaryKey($this->seller_id);
		return $seller;
	}
	
	function productCategories($order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductCategoryModel");
		return $model->findAllByProduct($this->product_id, $order, $reverse);
	}
	
	function hasCategory($category_id){
		$loader = new Clay_Plugin("Product");
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
		$loader = new Clay_Plugin("Product");
		$product = $loader->loadModel("CategoryModel");
		return $product->findAllBy($values, $order, $reverse);
	}

	function category($type_id){
		$categories = $this->categories(array("category_type_id" => $type_id));
		if(count($categories) > 0){
			return $categories[0];
		}
		$loader = new Clay_Plugin("Product");
		return $loader->loadModel("CategoryModel");
	}

	function productFlags($order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductFlagModel");
		return $model->findAllByProduct($this->product_id, $order, $reverse);
	}
	
	function hasFlag($flag_id){
		$loader = new Clay_Plugin("Product");
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
		$loader = new Clay_Plugin("Product");
		$product = $loader->loadModel("FlagModel");
		return $product->findAllBy($values, $order, $reverse);
	}
	
	function images(){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductImageModel");
		$images = $model->findAllByProduct($this->product_id);
		$result = array();
		foreach($images as $image){
			$result[$image->image_type] = $image;
		}
		return $result;
	}

	function image($image_type){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductImageModel");
		$model->findByPrimaryKey($this->product_id, $image_type);
		return $model;
	}

	function productOptions($order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductOptionModel");
		return $model->findAllByProduct($this->product_id, $order, $reverse);
	}

	function productOption($option1_id = 0, $option2_id = 0, $option3_id = 0, $option4_id = 0){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductOptionModel");
		$model->findByPrimaryKey($this->product_id, $option1_id, $option2_id, $option3_id, $option4_id);
		return $model;
	}
	
	function relatedProducts($limit = 0){
		// プラグインローダーの初期化
		$productPlugin = new Clay_Plugin("Product");
		$orderPlugin = new Clay_Plugin("Order");
		
		// 商品テーブルを生成
		$product = $productPlugin->loadTable("ProductsTable");
		
		// 受注明細テーブルを生成
		$orderDetail1 = $orderPlugin->loadTable("OrderDetailsTable");
		$orderDetail1->setAlias("order_details_1");
		$orderDetail2 = $orderPlugin->loadTable("OrderDetailsTable");
		$orderDetail2->setAlias("order_details_2");
		
		// 受注パッケージテーブルを作成
		$orderPackage1 = $orderPlugin->loadTable("OrderPackagesTable");
		$orderPackage1->setAlias("order_packages_1");
		$orderPackage2 = $orderPlugin->loadTable("OrderPackagesTable");
		$orderPackage2->setAlias("order_packages_2");
		
		// 受注テーブルを作成
		$order1 = $orderPlugin->loadTable("OrdersTable");
		$order1->setAlias("orders_1");
		$order2 = $orderPlugin->loadTable("OrdersTable");
		$order2->setAlias("orders_2");
		
		// SELECT文を作成
		$select = new Clay_Query_Select($product);
		$select->addColumn($product->_W)->addColumn("COUNT(".$product->product_id.")");
		$select->join($orderDetail1, array($orderDetail1->product_code." = ".$product->product_code));
		$select->join($orderPackage1, array($orderPackage1->order_package_id." = ".$orderDetail1->order_package_id));
		$select->join($order1, array($order1->order_id." = ".$orderPackage1->order_id));
		$select->join($order2, array($order1->customer_id." = ".$order2->customer_id));
		$select->join($orderPackage2, array($order2->order_id." = ".$orderPackage2->order_id));
		$select->join($orderDetail2, array($orderPackage2->order_package_id." = ".$orderDetail2->order_package_id));
		$select->addWhere($orderDetail2->product_code." = ?", array($this->product_code));
		$select->addWhere($product->product_code." <> ?", array($this->product_code));
		$select->addGroupBy($product->product_id)->addOrder("COUNT(".$product->product_id.")", true);
		if($limit > 0){
			$result = $select->execute($limit);
		}else{
			$result = $select->execute();
		}
		$products = array();
		foreach($result as $data){
			$products[] = new ProductModel($data);
		}
		return $products;
	}
}
?>