<?php
class Product_CategoryGroupsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_category_groups", "product");
	}
}
?>
