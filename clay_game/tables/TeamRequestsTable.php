<?php
class Game_TeamRequestsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_team_requests", "game");
	}
}
?>
