<?php
class Order_OrderStatusesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_order_statuses", "order");
	}
}
?>
