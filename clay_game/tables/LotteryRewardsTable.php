<?php
class Game_LotteryRewardsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("game");
		parent::__construct("game_lottery_rewards", "game");
	}
}
?>
