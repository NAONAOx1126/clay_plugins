<?php
class Movabletype_EntriesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("movabletype");
		parent::__construct("mt_entry", "movabletype");
	}
}
?>
