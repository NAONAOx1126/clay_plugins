<?php
/**
 * 受注詳細のデータモデルです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Plugins
 * @package   Shop
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Order_OrderDetailModel extends DatabaseModel{
	function __construct($values = array()){
		$loader = new PluginLoader("Order");
		parent::__construct($loader->loadTable("OrderDetailsTable"), $values);
	}
	
	function findByPrimaryKey($order_detail_id){
		$this->findBy(array("order_detail_id" => $order_detail_id));
	}
	
	function findByPackageProduct($order_package_id, $product_code, $option1_code = null, $option2_code = null, $option3_code = null, $option4_code = null){
		$this->findBy(array("order_package_id" => $order_package_id, "product_code" => $product_code, "option1_code" => $option1_code, "option2_code" => $option2_code, "option3_code" => $option3_code, "option4_code" => $option4_code));
	}
	
	function findAllByOrderPackage($order_package_id){
		return $this->findAllBy(array("order_package_id" => $order_package_id));
	}
	
	function package(){
		$loader = new PluginLoader("Order");
		$orderPackage = $loader->loadModel("OrderPackageModel");
		$orderPackage->findByPrimaryKey($this->order_package_id);		
		return $orderPackage;
	}
}
?>