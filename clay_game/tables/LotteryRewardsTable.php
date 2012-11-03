<?php
class Game_LotteryRewardsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_lottery_rewards", "game");
	}
}
?>
