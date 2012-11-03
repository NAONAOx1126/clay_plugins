<?php
class Base_PrefsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("base");
		parent::__construct("base_prefs", "base");
	}
}
?>
