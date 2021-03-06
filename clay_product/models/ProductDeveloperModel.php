<?php
/**
 * 顧客情報のモデルクラス
 */
class Product_ProductDeveloperModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Product");
		parent::__construct($loader->loadTable("ProductDevelopersTable"), $values);
	}
	
	function findByPrimaryKey($developer_id){
		$this->findBy(array("developer_id" => $developer_id));
	}
	
	function products($order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("ProductModel");
		return $model->findAllByDeveloper($this->developer_id, $order, $reverse);
	}
}
?>