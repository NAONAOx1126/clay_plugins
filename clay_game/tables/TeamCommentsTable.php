<?php
class Game_TeamCommentsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("game");
		parent::__construct("game_team_comments", "game");
	}
}
?>
