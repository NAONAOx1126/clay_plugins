<?php
/**
 * 新着情報のモデルクラス
 */
class Content_NewsModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Content");
		parent::__construct($loader->loadTable("NewsesTable"), $values);
	}
	
	public function findByPrimaryKey($news_id){
		$this->findBy(array("news_id" => $news_id));
	}
}
?>