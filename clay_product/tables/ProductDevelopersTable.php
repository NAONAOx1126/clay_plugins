<?php
class Product_ProductDevelopersTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_developers", "product");
	}
}
?>
