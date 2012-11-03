<?php
class Game_UnitSkillsTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = Clay_Database_Factory::getConnection("game");
		parent::__construct("game_unit_skills", "game");
	}
}
?>
