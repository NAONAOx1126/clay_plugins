<?php
class Game_EpisodeEncountersTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_episode_encounters", "game");
	}
}
?>
