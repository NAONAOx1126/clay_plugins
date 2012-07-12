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
 * ### Base.Company.Save
 * サイトのデータを保存する。
 */
class Base_Company_Save extends FrameworkModule{
	function execute($params){
		// サイトデータを取得する。
		$loader = new PluginLoader();
		$company = $loader->loadModel("CompanyModel");
		$company->findByPrimaryKey($_POST["company_id"]);
		foreach($_POST as $key => $value){
			$company->$key = $value;
		}
		
		// トランザクションの開始
		DBFactory::begin();
		
		if(empty($_POST["company_name"])){
			throw new InvalidException(array("組織名は必須です"));
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
			DBFactory::commit();
		}catch(Exception $e){
			DBFactory::rollBack();
			throw $e;
		}
	}
}
?>
