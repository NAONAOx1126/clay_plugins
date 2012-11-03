<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   3.0.0
 */

/**
 * ### Base.Company.List
 * サイトデータのリストを取得する。
 */
class Base_Company_List extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin();
		if($_SESSION["OPERATOR"]["super_flg"] != 1){
			$site = $loader->loadModel("SiteModel");
			$site->findByPrimaryKey(array("site_id" => $_SERVER["CONFIGURE"]->site_id));
			$companys = $site->companys();
		}else{
			$company = $loader->loadModel("CompanyModel");
			$companys = $company->findAllBy(array());
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "companys")] = $companys;
	}
}
?>
