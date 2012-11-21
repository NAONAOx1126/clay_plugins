<?php
class Content_ActivePageKeysTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("content");
		parent::__construct("content_active_page_keys", "content");
	}
}
?>
