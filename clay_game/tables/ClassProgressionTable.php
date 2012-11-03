<?php
class Game_ClassProgressionTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_class_progression", "game");
	}
}
?>
