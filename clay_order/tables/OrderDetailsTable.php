<?php
class Order_OrderDetailsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_order_details", "order");
	}
}
?>
