<?php
class Movabletype_CategoriesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("movabletype");
		parent::__construct("mt_category", "movabletype");
	}
}
?>
