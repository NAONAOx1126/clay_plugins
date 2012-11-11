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
 * ### Base.Company.Save
 * サイトのデータを保存する。
 */
class Base_Company_Save extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin();
		$company = $loader->loadModel("CompanyModel");
		$company->findByPrimaryKey($_POST["company_id"]);
		foreach($_POST as $key => $value){
			$company->$key = $value;
		}
		
		// トランザクションの開始
		Clay_Database_Factory::begin();
		
		if(empty($_POST["company_name"])){
			throw new Clay_Exception_Invalid(array("組織名は必須です"));
		}
		
		try{
			$company->save();
			
			if(isset($_POST["site_id"]) && is_array($_POST["site_id"])){
				$siteCompanys = $company->siteCompanys();
				$saveSites = array();
				$deleteSites = array();
				foreach($siteCompanys as $siteCompany){
					if(in_array($siteCompany->site_id, $_POST["site_id"])){
						$saveSites[$siteCompany->site_id] = $siteCompany;
					}else{
						$deleteSites[$siteCompany->site_id] = $siteCompany;
					}
				}
				foreach($_POST["site_id"] as $site_id){
					if(!isset($saveSites[$site_id])){
						$saveSites[$site_id] = $loader->loadModel("SiteCompanyModel", array("site_id" => $site_id, "company_id" => $company->company_id));
					}
				}
				foreach($saveSites as $saveSite){
					$saveSite->save();
				}
				foreach($deleteSites as $deleteSite){
					$deleteSite->delete();
				}
			}

			// エラーが無かった場合、処理をコミットする。
			Clay_Database_Factory::commit();
		}catch(Exception $e){
			Clay_Database_Factory::rollBack();
			throw $e;
		}
	}
}
