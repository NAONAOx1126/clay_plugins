<?php
class Product_ProductFlagsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_flags", "product");
	}
}
?>
