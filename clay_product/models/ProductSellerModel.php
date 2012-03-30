<?php
/**
 * 顧客情報のモデルクラス
 */
class Product_ProductSellerModel extends DatabaseModel{
	function __construct($values = array()){
		$loader = new PluginLoader("Product");
		parent::__construct($loader->loadTable("ProductSellersTable"), $values);
	}
	
	function findByPrimaryKey($seller_id){
		$this->findBy(array("seller_id" => $seller_id));
	}
	
	function products($order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("ProductModel");
		return $model->findAllBySeller($this->seller_id, $order, $reverse);
	}
}
?>