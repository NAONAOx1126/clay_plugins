<?php
class Movabletype_MailRequestPartsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("movabletype");
		parent::__construct("mt_mail_request_parts", "movabletype");
	}
}
?>
