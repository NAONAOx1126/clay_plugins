<?php
class Order_TempOrderDetailsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_temp_order_details", "order");
	}
}
?>
