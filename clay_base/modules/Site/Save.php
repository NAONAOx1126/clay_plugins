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
 * ### Base.Site.Save
 * サイトのデータを保存する。
 */
class Base_Site_Save extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin();
		$site = $loader->loadModel("SiteModel");
		$site->findByPrimaryKey($_POST["site_id"]);
		foreach($_POST as $key => $value){
			$site->$key = $value;
		}
		
		// トランザクションの開始
		DBFactory::begin();
		
		if($site->site_code == ""){
			throw new Clay_Exception_Invalid(array("サイトコードは必須です"));
		}

		$site2 = $loader->loadModel("SiteModel");
		$site2->findBySiteCode($site->site_code);
		if($site->site_id != $site2->site_id && $site->site_code == $site2->site_code){
			throw new Clay_Exception_Invalid(array("サイトコードは重複できません"));
		}

		$site2->findByDomainName($site->domain_name);
		if($site->site_id != $site2->site_id && $site->domain_name == $site2->domain_name){
			throw new Clay_Exception_Invalid(array("ドメイン名は重複できません"));
		}
		
		try{
			$site->save();
					
			// エラーが無かった場合、処理をコミットする。
			DBFactory::commit();
		}catch(Exception $e){
			DBFactory::rollBack();
			throw $e;
		}
	}
}
?>
