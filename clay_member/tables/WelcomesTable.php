<?php
class Member_WelcomesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_welcomes", "member");
	}
}
?>
