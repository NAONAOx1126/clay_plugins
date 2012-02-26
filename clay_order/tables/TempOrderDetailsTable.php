<?php
class Order_TempOrderDetailsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_temp_order_details", "order");
	}
}
?>
