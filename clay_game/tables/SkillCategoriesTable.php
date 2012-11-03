<?php
class Game_SkillCategoriesTable extends Clay_Plugin_Table{
	function __construct(){
		$this->db = DBFactory::getConnection("game");
		parent::__construct("game_skill_categories", "game");
	}
}
?>
