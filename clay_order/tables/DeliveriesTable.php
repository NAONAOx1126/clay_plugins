<?php
class Order_DeliveriesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_deliveries", "order");
	}
}
?>
