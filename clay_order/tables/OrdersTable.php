<?php
class Order_OrdersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_orders", "order");
	}
}
?>
