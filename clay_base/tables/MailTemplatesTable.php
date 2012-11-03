<?php
class Base_MailTemplatesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("base");
		parent::__construct("base_mail_templates", "base");
	}
}
?>
