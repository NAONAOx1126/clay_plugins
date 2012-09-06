<?php
class Movabletype_MailRequestPartsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("movabletype");
		parent::__construct("mt_mail_request_parts", "movabletype");
	}
}
?>
