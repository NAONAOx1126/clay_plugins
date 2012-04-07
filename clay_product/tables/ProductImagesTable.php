<?php
class Product_ProductImagesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("product");
		parent::__construct("shop_product_images", "product");
	}
}
?>
