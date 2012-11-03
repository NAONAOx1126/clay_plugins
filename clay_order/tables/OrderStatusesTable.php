<?php
class Order_OrderStatusesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("order");
		parent::__construct("shop_order_statuses", "order");
	}
}
?>
