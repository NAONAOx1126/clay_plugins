<?php
class Product_ProductCategoriesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_categories", "product");
	}
}
?>
