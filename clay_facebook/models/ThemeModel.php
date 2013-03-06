<?php
$loader = new Clay_Plugin("facebook");
$loader->LoadCommon("Facebook");

/**
 * CSVファイルのファイル情報を扱うモデルです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Models
 * @package   File
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Facebook_ThemeModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("facebook");
		parent::__construct($loader->loadTable("ThemesTable"), $values);
	}
	
	function findByPrimaryKey($theme_id){
		$this->findBy(array("theme_id" => $theme_id));
	}
}
?>