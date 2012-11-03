<?php
class Base_SitesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_sites", "base");
	}
}
?>
