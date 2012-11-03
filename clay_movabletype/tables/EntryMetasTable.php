<?php
class Movabletype_EntryMetasTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("movabletype");
		parent::__construct("mt_entry_meta", "movabletype");
	}
}
?>
