<?php
class Product_OptionTypesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_option_types", "product");
	}
}
?>
