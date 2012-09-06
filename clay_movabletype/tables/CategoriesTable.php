<?php
class Movabletype_CategoriesTable extends DatabaseTable{
	function __construct(){
		$this->db = DBFactory::getConnection("movabletype");
		parent::__construct("mt_category", "movabletype");
	}
}
?>
