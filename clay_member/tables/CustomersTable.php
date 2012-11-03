<?php
class Member_CustomersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("member");
		parent::__construct("member_customers", "member");
	}
}
?>
