<?php
class Product_CategoriesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_categories", "product");
	}
}
?>
