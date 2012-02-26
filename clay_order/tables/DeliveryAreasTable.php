<?php
class Order_DeliveryAreasTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_delivery_areas", "order");
	}
}
?>
