<?php
class Content_NewsesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("content");
		parent::__construct("content_newses", "content");
	}
}
?>
