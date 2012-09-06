<?php
class Movabletype_PlacementsTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("movabletype");
		parent::__construct("mt_placement", "movabletype");
	}
}
?>
