<?php
class Game_CollectionRewardsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_collection_rewards", "game");
	}
}
?>
