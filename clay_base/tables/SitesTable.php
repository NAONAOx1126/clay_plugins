<?php
class Base_SitesTable extends Clay_Plugin_Table{
	public function __construct(){
		$this->db = Clay_Database_Factory::getConnection("base");
		parent::__construct("base_sites", "base");
	}
	
	public function install($connection){
		$columns = array(
			array("name" => "site_id", "type" => "int", "size" => 11, "auto" => true), 
			array("name" => "site_code", "type" => "varchar", "size" => 100, "null" => false), 
			array("name" => "site_password", "type" => "varchar", "size" => 100, "null" => true), 
			array("name" => "site_name", "type" => "text", "null" => false), 
			array("name" => "domain_name", "type" => "varchar", "size" => 200, "null" => false), 
			array("name" => "site_home", "type" => "text", "null" => false), 
			array("name" => "create_time", "type" => "datetime", "null" => false), 
			array("name" => "update_time", "type" => "datetime", "null" => false)
		);
		$keys = array("site_id");
		$uniques = array(
			"site_code" => array("site_code"),
			"domain_name" => array("domain_name")
		);
		$indexes = array(
		);
		$datas = array(
			array("site_code" => "default", "site_name" => "デフォルトサイト", "domain_name" => "localhost", "site_home" => realpath(CLAY_ROOT.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."clay_demo"))
		);
		$this->createTable($connection, $columns, $keys, $uniques, $indexes, $datas);
	}
}
?>
