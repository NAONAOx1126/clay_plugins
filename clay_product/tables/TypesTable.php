<?php
class Shop_TypesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_types", "product");
	}
}
?>
