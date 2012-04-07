<?php
class Product_ProductsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_products", "product");
	}
}
?>
