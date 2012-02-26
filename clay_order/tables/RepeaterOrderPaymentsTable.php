<?php
class Order_RepeaterOrderPaymentsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_repeater_order_payments", "order");
	}
}
?>
