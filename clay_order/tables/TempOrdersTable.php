<?php
class Order_TempOrdersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_temp_orders", "order");
	}
}
?>
