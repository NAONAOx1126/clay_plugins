<?php
class Session_StoresTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("session");
		parent::__construct("session_stores", "session");
	}
}
?>
