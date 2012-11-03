<?php
class Member_SerialLogsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_serial_logs", "member");
	}
}
?>
