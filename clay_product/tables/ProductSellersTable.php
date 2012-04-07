<?php
class Product_ProductSellersTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_sellers", "product");
	}
}
?>
