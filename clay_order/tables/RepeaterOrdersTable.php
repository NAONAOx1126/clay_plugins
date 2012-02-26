<?php
class Order_RepeaterOrdersTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_repeater_orders", "order");
	}
}
?>
