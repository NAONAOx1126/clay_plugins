<?php
class Content_AdvertisesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("content");
		parent::__construct("content_advertises", "content");
	}
}
?>
