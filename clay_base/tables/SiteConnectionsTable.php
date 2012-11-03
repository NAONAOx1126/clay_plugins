<?php
class Base_SiteConnectionsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_site_connections", "base");
	}
}
?>
