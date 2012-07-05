<?php
class Member_WelcomeCodesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_welcome_codes", "member");
	}
}
?>
