<?php
class Game_EpisodesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_episodes", "game");
	}
}
?>
