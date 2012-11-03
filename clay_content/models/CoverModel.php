<?php
/**
 * カバー画像のモデルクラス
 */
class Content_CoverModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Content");
		parent::__construct($loader->loadTable("CoversTable"), $values);
	}
	
	public function findByPrimaryKey($cover_id){
		$this->findBy(array("cover_id" => $cover_id));
	}
}
?>