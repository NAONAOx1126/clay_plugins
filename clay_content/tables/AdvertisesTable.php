<?php
class Content_AdvertisesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("content");
		parent::__construct("content_advertises", "content");
	}
}
?>
