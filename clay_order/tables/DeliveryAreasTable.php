<?php
class Order_DeliveryAreasTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("order");
		parent::__construct("shop_delivery_areas", "order");
	}
}
?>
