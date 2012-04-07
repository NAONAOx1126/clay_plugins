<?php
class Product_FlagsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_flags", "product");
	}
}
?>
