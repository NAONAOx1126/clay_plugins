<?php
class Member_SerialLogsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("member");
		parent::__construct("member_serial_logs", "member");
	}
}
?>
