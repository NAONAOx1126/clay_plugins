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
class Product_CategoryGroupModel extends DatabaseModel{
	function __construct($values = array()){
		$loader = new PluginLoader("Product");
		parent::__construct($loader->loadTable("CategoryGroupsTable"), $values);
	}
	
	function findByPrimaryKey($category_group_id){
		$this->findBy(array("category_group_id" => $category_group_id));
	}
	
	function categories($order = "", $reverse = false){
		$loader = new PluginLoader("Product");
		$model = $loader->loadModel("CategoryModel");
		return $model->findAllByGroup($this->category_group_id, $order, $reverse);
	}
}
?>