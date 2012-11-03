<?php
class Member_CustomersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_customers", "member");
	}
}
?>
