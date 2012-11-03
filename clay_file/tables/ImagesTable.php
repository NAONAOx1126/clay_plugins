<?php
class File_ImagesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("file");
		parent::__construct("file_images", "file");
	}
}
?>
