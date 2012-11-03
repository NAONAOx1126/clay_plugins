<?php
class Product_ProductTypesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_types", "product");
	}
}
?>
