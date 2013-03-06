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
class Facebook_GroupModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("facebook");
		parent::__construct($loader->loadTable("GroupsTable"), $values);
	}
	
	function findByPrimaryKey($group_id){
		$this->findBy(array("group_id" => $group_id));
	}
	
	function findByFacebookId($facebook_id){
		$this->findBy(array("facebook_id" => $facebook_id));
	}
	
	function findAllByCompany($company_id, $order = "", $reverse = false){
		return $this->findAllBy(array("company_id" => $company_id), $order, $reverse);
	}
	
	function findAllByTheme($theme_id, $order = "", $reverse = false){
		return $this->findAllBy(array("theme_id" => $theme_id), $order, $reverse);
	}
	
	function theme(){
		$loader = new Clay_Plugin("Facebook");
		$theme = $loader->loadModel("ThemeModel");
		$theme->findByPrimaryKey($this->theme_id);
		return $theme;
	}
	
	function posts(){
		$loader = new Clay_Plugin("Facebook");
		$post = $loader->loadModel("PostModel");
		return $post->findAllByGroup($this->group_id);
	}
	
	function reports(){
		$loader = new Clay_Plugin("Facebook");
		$report = $loader->loadModel("ReportModel");
		return $report->findAllByGroup($this->group_id);
	}
	
	function facebook_members(){
		if($_SERVER["ATTRIBUTES"]["facebook"]){
			$members = $_SERVER["ATTRIBUTES"]["facebook"]->api("/".$this->facebook_id."/members");
			return $members["data"];
		}
	}
}
?>