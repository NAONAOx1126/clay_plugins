<?php
class Content_CoversTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("content");
		parent::__construct("content_covers", "content");
	}
}
?>
