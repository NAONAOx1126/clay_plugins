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
 * ### Address.ZipAddress
 * 郵便番号から住所を検索するためのクラスです。
 *
 * @param login Basic認証のログインID
 * @param password Basic認証のパスワード
 * @param text 認証ダイアログのメッセージ
 * @param error エラー時のメッセージ
 */
class Address_ZipAddress extends Clay_Plugin_Module{
	function execute($params){
		if(!empty($_POST["search_zip"])){
			if($params->check("key") && isset($_POST[$params->get("key")])){
				$zip1Key = $params->get("zip1", "zip1");
				$zip2Key = $params->get("zip2", "zip2");
				$prefKey = $params->get("pref", "pref");
				$address1Key = $params->get("address1", "address1");
				
				// 郵便番号を住所情報に変換
				$loader = new Clay_Plugin("Address");
				$zip = $loader->loadModel("ZipModel");
				$zip->findByCode($_POST[$zip1Key].$_POST[$zip2Key]);
				
				// 都道府県をIDに変換
				$pref = $loader->loadModel("PrefModel");
				$pref->findByName($zip->state);
				$zip->state_id = $pref->id;
				
				// 結果を格納
				$_POST[$prefKey] = $zip->state_id;
				$_POST[$address1Key] = $zip->city.$zip->town;
				
				$this->removeInput("search_zip");
				if($params->check("redirect")){
					$this->redirect($params->get("redirect"));
				}
			}
		}
	}
}
?>
