<?php
class Base_MailTemplatesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_mail_templates", "base");
	}
}
?>
