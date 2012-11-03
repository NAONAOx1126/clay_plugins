<?php
class Order_TempOrdersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("order");
		parent::__construct("shop_temp_orders", "order");
	}
}
?>
