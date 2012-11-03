<?php
class Base_SiteCompanysTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_site_companys", "base");
	}
}
?>
