<?php
class Member_CustomerOptionsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_customer_options", "member");
	}
}
?>
