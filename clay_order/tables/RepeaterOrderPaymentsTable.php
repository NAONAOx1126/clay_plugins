<?php
class Order_RepeaterOrderPaymentsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("order");
		parent::__construct("shop_repeater_order_payments", "order");
	}
}
?>
