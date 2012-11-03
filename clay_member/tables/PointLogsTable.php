<?php
class Member_PointLogsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_point_logs", "member");
	}
}
?>
