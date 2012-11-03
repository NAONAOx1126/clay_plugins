<?php
class Movabletype_PlacementsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("movabletype");
		parent::__construct("mt_placement", "movabletype");
	}
}
?>
