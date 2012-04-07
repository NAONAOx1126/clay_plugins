<?php
class Product_OptionsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_options", "product");
	}
}
?>
