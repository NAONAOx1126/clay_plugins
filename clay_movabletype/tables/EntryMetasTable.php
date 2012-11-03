<?php
class Movabletype_EntryMetasTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("movabletype");
		parent::__construct("mt_entry_meta", "movabletype");
	}
}
?>
