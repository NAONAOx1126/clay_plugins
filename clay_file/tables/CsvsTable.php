<?php
class File_CsvsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("file");
		parent::__construct("file_csvs", "file");
	}
}
?>
