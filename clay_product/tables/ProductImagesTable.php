<?php
class Product_ProductImagesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("shop");
		parent::__construct("shop_product_images", "shop");
	}
}
?>
