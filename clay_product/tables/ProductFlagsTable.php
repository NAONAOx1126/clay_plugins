<?php
class Product_ProductFlagsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("product");
		parent::__construct("shop_product_flags", "product");
	}
}
?>
