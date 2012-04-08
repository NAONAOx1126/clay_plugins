<?php
class Member_CustomerContractsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_customer_contracts", "member");
	}
}
?>
