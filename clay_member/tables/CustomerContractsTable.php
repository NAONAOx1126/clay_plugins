<?php
class Member_CustomerContractsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("member");
		parent::__construct("member_customer_contracts", "member");
	}
}
?>
