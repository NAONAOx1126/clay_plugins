<?php
class File_UploadLogsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("file");
		parent::__construct("file_upload_logs", "file");
	}
}
?>
