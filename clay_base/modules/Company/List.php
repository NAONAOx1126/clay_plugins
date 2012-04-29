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
class Base_Company_List extends FrameworkModule{
	function execute($params){
		// サイトデータを取得する。
		$loader = new PluginLoader();
		$companys = $loader->loadTable("CompanysTable");
		$prefs = $loader->loadTable("PrefsTable");
		$companyOperators = $loader->loadTable("CompanyOperatorsTable");

		$select = new DatabaseSelect($companys);
		$select->addColumn($companys->_W);
		$select->joinLeft($prefs, array($companys->pref." = ".$prefs->id));
		$select->joinLeft($companyOperators, array($companys->company_id." = ".$companyOperators->company_id));
		if(!empty($_POST["search_company_name"])){
			$select->addWhere($companys->company_name." LIKE ?", array("%".$_POST["search_company_name"]."%"));
		}
		if(!empty($_POST["search_company_address"])){
			$select->addWhere("CONCAT(".$prefs->name.", ".$companys->address1.", ".$companys->address2.") LIKE ?", array("%".$_POST["search_company_address"]."%"));
		}
		if(!empty($_POST["search_company_tel"])){
			$select->addWhere("CONCAT(".$companys->name.", ".$companys->address1.", ".$companys->address2.") LIKE ?", array("%".$_POST["search_operator_name"]."%"));
		}
		if(!empty($_POST["search_operator_title"])){
			$select->addWhere($companyOperators->operator_title." LIKE ?", array("%".$_POST["search_operator_title"]."%"));
		}
		if(!empty($_POST["search_operator_name"])){
			$select->addWhere($companyOperators->operator_name." LIKE ?", array("%".$_POST["search_operator_name"]."%"));
		}
		if(!empty($_POST["search_operator_name"])){
			$select->addWhere($companyOperators->operator_name." LIKE ?", array("%".$_POST["search_operator_name"]."%"));
		}

		$sites = $site->findAllBy(array());
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "sites")] = $sites;
	}
}
?>
