<?php
class Product_CategoryGroupsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("product");
		parent::__construct("shop_category_groups", "product");
	}
}
?>
