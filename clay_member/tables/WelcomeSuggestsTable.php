<?php
class Member_WelcomeSuggestsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_welcome_suggests", "member");
	}
}
?>
