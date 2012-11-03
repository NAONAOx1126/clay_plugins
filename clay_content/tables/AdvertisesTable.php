<?php
class Content_AdvertisesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("content");
		parent::__construct("content_advertises", "content");
	}
}
?>
