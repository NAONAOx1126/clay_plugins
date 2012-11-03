<?php
class Product_ProductSellersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("product");
		parent::__construct("shop_product_sellers", "product");
	}
}
?>
