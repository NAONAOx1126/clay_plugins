<?php
class Content_CoversTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("content");
		parent::__construct("content_covers", "content");
	}
}
?>
