<?php
class Member_WelcomeCodesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_welcome_codes", "member");
	}
}
?>
