<?php
class Game_UnitsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_units", "game");
	}
}
?>
