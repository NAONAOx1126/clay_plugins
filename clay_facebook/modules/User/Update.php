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

$loader = new Clay_Plugin("facebook");
$loader->LoadCommon("Facebook");

/**
 * ### Admin.Detail
 * Facebookのログイン処理を実行し、ユーザーの情報を更新する。
 */
class Facebook_User_Update extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_SERVER["ATTRIBUTES"]["facebook"])){
			$loader = new Clay_Plugin("facebook");
			$loader->LoadSetting();
			$user = $loader->loadModel("UserModel");
				
			$fbUser = $_SERVER["ATTRIBUTES"]["facebook"]->api("/me");
			if(!empty($fbUser["id"])){
				$user->findByFacebookId($fbUser["id"]);

				foreach($_POST as $name => $value){
					$user->$name = $value;
				}
				
				// トランザクションの開始
				Clay_Database_Factory::begin();
			
				try{
					$user->save();
		
					// エラーが無かった場合、処理をコミットする。
					Clay_Database_Factory::commit();
				}catch(Exception $e){
					Clay_Database_Factory::rollBack();
					throw $e;
				}
			}
			
			// データを取得する。
			$_SERVER["ATTRIBUTES"][$params->get("result", "target")] = $_SERVER["ATTRIBUTES"]["facebook"]->api("/".$targetId);
		}
	}
}
