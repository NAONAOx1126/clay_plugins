<?php
class Member_CustomerOptionsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("member");
		parent::__construct("member_customer_options", "member");
	}
}
?>
