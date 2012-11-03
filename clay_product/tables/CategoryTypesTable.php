<?php
class Product_CategoryTypesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_category_types", "product");
	}
}
?>
