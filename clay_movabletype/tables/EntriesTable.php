<?php
class Movabletype_EntriesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("movabletype");
		parent::__construct("mt_entry", "movabletype");
	}
}
?>
