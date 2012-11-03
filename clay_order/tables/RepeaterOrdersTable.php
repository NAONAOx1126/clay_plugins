<?php
class Order_RepeaterOrdersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_repeater_orders", "order");
	}
}
?>
