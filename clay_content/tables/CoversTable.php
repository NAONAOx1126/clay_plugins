<?php
class Content_CoversTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("content");
		parent::__construct("content_covers", "content");
	}
}
?>
