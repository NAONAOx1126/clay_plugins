<?php
class Product_ProductTypesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_types", "product");
	}
}
?>
