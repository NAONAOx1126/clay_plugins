<?php
class Order_OrdersTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_orders", "order");
	}
}
?>
