<?php
class Product_OptionsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_options", "product");
	}
}
?>
