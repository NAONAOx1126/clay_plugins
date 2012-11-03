<?php
class Order_RepeaterOrderDetailsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_repeater_order_details", "order");
	}
}
?>
