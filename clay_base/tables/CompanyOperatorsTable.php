<?php
class Base_CompanyOperatorsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("base");
		parent::__construct("base_company_operators", "base");
	}
}
?>
