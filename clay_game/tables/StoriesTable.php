<?php
class Game_StoriesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_stories", "game");
	}
}
?>
