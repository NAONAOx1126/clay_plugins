<?php
class Member_PointRulesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_point_rules", "member");
	}
}
?>
