<?php
class Movabletype_MailRequestsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("movabletype");
		parent::__construct("mt_mail_request", "movabletype");
	}
}
?>
