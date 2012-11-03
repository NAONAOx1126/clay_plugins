<?php
class File_CsvContentsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("file");
		parent::__construct("file_csv_contents", "file");
	}
}
?>
