<?php
/**
 * 商品フラグのデータモデルです。
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
class Product_FlagModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Product");
		parent::__construct($loader->loadTable("FlagsTable"), $values);
	}
	
	function findByPrimaryKey($flag_id){
		$this->findBy(array("flag_id" => $flag_id));
	}

	function productFlags($order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$productFlag = $loader->loadModel("ProductFlagModel");
		return $productFlag->findAllByFlag($this->flag_id, $order, $reverse);
	}
	
	function products($values = array(), $order = "", $reverse = false){
		$productFlags = $this->productFlags();
		if(!is_array($values)){
			$values = array();
		}
		$values["in:product_id"] = array();
		foreach($productFlags as $item){
			$values["in:product_id"][] = $item->product_id;
		}
		$product = $loader->loadModel("ProductModel");
		return $product->findAllBy($values, $order, $reverse);
	}
}
?>