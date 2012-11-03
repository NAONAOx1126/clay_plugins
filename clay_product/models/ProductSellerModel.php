<?php
/**
 * 顧客情報のモデルクラス
 */
class Product_ProductSellerModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Product");
		parent::__construct($loader->loadTable("ProductSellersTable"), $values);
	}
	
	function findByPrimaryKey($seller_id){
		$this->findBy(array("seller_id" => $seller_id));
	}
	
	function products($order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductModel");
		return $model->findAllBySeller($this->seller_id, $order, $reverse);
	}
}
?>