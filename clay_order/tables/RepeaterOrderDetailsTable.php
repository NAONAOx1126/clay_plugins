<?php
class Order_RepeaterOrderDetailsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_repeater_order_details", "order");
	}
}
?>
