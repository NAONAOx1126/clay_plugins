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
 * ### Facebook.Theme.Save
 * テーマを保存する。
 */
class Facebook_Report_Save extends Clay_Plugin_Module_Save{
	function execute($params){
		// レポートファイルをアップロード
		if(is_array($_FILES["upload"]["name"])){
			foreach($_FILES["upload"]["name"] as $key => $value){
				if($_FILES["upload"]["error"][$key] == 0){
					// エラーで無い場合はアップロード
					$upfile_name = sha1("report".uniqid());
					if(!is_dir($_SERVER["CONFIGURE"]->site_home."/upload/facebook_report/")){
						mkdir($_SERVER["CONFIGURE"]->site_home."/upload/facebook_report/");
					}
					
					// 元のファイル名から拡張子を取得する。
					if(strrpos($value, ".") > 0){
						$ext = substr($value, strrpos($value, "."));
					}else{
						$ext = "";
					}
					
					move_uploaded_file($_FILES["upload"]["tmp_name"][$key], $_SERVER["CONFIGURE"]->site_home."/upload/facebook_report/".$upfile_name.$ext);
					$_POST[str_replace("report_file", "report_type", $key)] = $_FILES["upload"]["type"][$key];
					$_POST[$key] = $upfile_name.$ext;
				}
			}
		}
		
		// グループから組織IDを取得
		$loader = new Clay_Plugin("Facebook");
		$loader->LoadSetting();
		$group = $loader->LoadModel("GroupModel");
		$group->findByPrimaryKey($_POST["group_id"]);
		$_POST["company_id"] = $group->company_id;
		
		// レポートを登録
		$this->executeImpl("Facebook", "ReportModel", "report_id");
	}
}
