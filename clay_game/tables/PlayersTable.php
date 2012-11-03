<?php
class Game_PlayersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_players", "game");
	}
}
?>
