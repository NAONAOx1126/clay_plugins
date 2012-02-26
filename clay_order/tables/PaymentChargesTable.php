<?php
class Order_PaymentChargesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("order");
		parent::__construct("shop_payment_charges", "order");
	}
}
?>
