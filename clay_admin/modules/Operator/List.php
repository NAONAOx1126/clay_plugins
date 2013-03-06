<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */

/**
 * ### Base.Company.List
 * サイトデータのリストを取得する。
 */
class Admin_Company_List extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin("admin");
		$company = $loader->loadModel("CompanyModel");
		$companys = $company->findAllBy(array());
		$_SERVER["ATTRIBUTES"][$params->get("result", "companys")] = $companys;
	}
}
