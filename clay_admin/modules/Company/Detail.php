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
 * ### Base.Company.Detail
 * サイトの詳細データを取得する。
 */
class Admin_Company_Detail extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin("Admin");
		$company = $loader->loadModel("CompanyModel");
		$company->findByPrimaryKey($_POST["company_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "company")] = $company;
	}
}
