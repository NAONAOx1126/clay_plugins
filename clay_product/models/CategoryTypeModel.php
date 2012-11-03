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
class Product_CategoryTypeModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Product");
		parent::__construct($loader->loadTable("CategoryTypesTable"), $values);
	}
	
	function findByPrimaryKey($category_type_id){
		$this->findBy(array("category_type_id" => $category_type_id));
	}
	
	function categories($order = "", $reverse = false){
		$loader = new Clay_Plugin("Product");
		$model = $loader->loadModel("CategoryModel");
		return $model->findAllByType($this->category_type_id, $order, $reverse);
	}
}
?>