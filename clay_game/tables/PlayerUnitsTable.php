<?php
class Game_PlayerUnitsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_player_units", "game");
	}
}
?>
