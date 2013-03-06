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
class Facebook_ReportModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("facebook");
		parent::__construct($loader->loadTable("ReportsTable"), $values);
	}
	
	function findByPrimaryKey($report_id){
		$this->findBy(array("report_id" => $report_id));
	}
	
	function findAllByGroup($group_id, $order = "", $reverse = false){
		return $this->findAllBy(array("group_id" => $group_id), $order, $reverse);
	}

	function group(){
		$loader = new Clay_Plugin("Facebook");
		$theme = $loader->loadModel("GroupThemeModel");
		$theme->findByPrimaryKey($this->theme_id);
		return $theme;
	}
	
	function findAllByCompany($company_id, $order = "", $reverse = false){
		return $this->findAllBy(array("company_id" => $company_id), $order, $reverse);
	}
	
	function company(){
		$loader = new Clay_Plugin("Admin");
		$theme = $loader->loadModel("CompanyModel");
		$theme->findByPrimaryKey($this->company_id);
		return $theme;
	}
}
?>