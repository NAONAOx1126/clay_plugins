<?php
class Base_LogsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_logs", "base");
	}
}
?>
