<?php
class Base_MaillogsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_maillogs", "base");
	}
}
?>
