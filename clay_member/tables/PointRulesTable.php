<?php
class Member_PointRulesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_point_rules", "member");
	}
}
?>
