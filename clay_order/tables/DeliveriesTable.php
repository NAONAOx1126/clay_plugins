<?php
class Order_DeliveriesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_deliveries", "order");
	}
}
?>
