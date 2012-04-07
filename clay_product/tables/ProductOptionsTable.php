<?php
class Product_ProductOptionsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_options", "product");
	}
}
?>
