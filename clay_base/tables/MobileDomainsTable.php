<?php
class Base_MobileDomainsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("base");
		parent::__construct("base_mobile_domains", "base");
	}
}
?>
