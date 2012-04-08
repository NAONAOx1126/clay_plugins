<?php
class Member_ContractsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_contracts", "member");
	}
}
?>
