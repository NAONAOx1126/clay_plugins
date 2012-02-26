<?php
class Order_OrderPackagesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_order_packages", "order");
	}
}
?>
