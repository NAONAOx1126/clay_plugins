<?php
class Content_NewsesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("content");
		parent::__construct("content_newses", "content");
	}
}
?>
