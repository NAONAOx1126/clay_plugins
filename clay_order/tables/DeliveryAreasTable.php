<?php
class Order_DeliveryAreasTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_delivery_areas", "order");
	}
}
?>
