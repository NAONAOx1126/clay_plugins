<?php
class Member_AdvertisesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("member");
		parent::__construct("member_advertises", "member");
	}
}
?>
