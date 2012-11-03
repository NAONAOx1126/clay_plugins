<?php
class Session_StoresTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("session");
		parent::__construct("session_stores", "session");
	}
}
?>
