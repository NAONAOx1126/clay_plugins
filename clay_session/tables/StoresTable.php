<?php
class Session_StoresTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("session");
		parent::__construct("session_stores", "session");
	}
}
?>
