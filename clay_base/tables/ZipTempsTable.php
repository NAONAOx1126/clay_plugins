<?php
class Base_ZipTempsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_zip_temps", "base");
	}
}
?>
