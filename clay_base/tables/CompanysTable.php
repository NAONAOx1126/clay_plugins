<?php
class Base_CompanysTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_companys", "base");
	}
}
?>
