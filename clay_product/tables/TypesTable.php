<?php
class Shop_TypesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_types", "product");
	}
}
?>
