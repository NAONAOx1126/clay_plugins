<?php
class Product_ProductProfitsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_profits", "product");
	}
}
?>