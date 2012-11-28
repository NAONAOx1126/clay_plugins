<?php
class Checkin_MusicCategoriesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("checkin");
		parent::__construct("checkin_music_categories", "checkin");
	}
}
?>
