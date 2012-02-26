<?php
class Order_PaymentsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_payments", "order");
	}
}
?>
