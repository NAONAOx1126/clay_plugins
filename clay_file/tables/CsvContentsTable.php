<?php
class File_CsvContentsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("file");
		parent::__construct("file_csv_contents", "file");
	}
}
?>
