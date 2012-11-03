<?php
class Member_ContractsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("member");
		parent::__construct("member_contracts", "member");
	}
}
?>
