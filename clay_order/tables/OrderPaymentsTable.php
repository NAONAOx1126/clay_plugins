<?php
class Order_OrderPaymentsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_order_payments", "order");
	}
}
?>
