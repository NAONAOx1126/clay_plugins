<?php
/**
 * 短縮URLのモデルクラス
 */
class Content_ShortcutModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Content");
		parent::__construct($loader->loadTable("ShortcutsTable"), $values);
	}
	
	public function findByPrimaryKey($shortcut_id){
		$this->findBy(array("shortcut_id" => $shortcut_id));
	}
}
?>