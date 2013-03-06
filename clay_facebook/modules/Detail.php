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
 * 
 */
class Facebook_Detail extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_SERVER["ATTRIBUTES"]["facebook"])){
			// targetにIDが指定されている場合にはそのIDを、keyにターゲット用のキーが
			// 設定されている場合にはPOSTからそのキーで、いずれも設定されていない場合はmeとする。
			$targetKey = $params->get("key", "");
			$targetId = $params->get("target", (!empty($targetKey)?$_POST[$targetKey]:"me"));
			
			// IDが取得できていない場合はmeを設定
			if(empty($targetId)){
				$targetId = "me";
			}
			
			$loader = new Clay_Plugin("facebook");
			$loader->LoadSetting();
			$user = $loader->loadModel("UserModel");
				
			$fbUser = $_SERVER["ATTRIBUTES"]["facebook"]->api("/".$targetId);
			if(!empty($fbUser["id"])){
				$user->findByFacebookId($fbUser["id"]);
				$user->facebook_id = $fbUser["id"];
				$user->name = $fbUser["name"];
				$user->first_name = $fbUser["first_name"];
				$user->last_name = $fbUser["last_name"];
				$user->gender = $fbUser["gender"];
				$user->locale = $fbUser["locale"];
				$user->username = $fbUser["username"];
				$user->link = $fbUser["link"];
				$user->birthday = date("Y-m-d 00:00:00", strtotime($fbUser["birthday"]));
				$user->email = $fbUser["email"];
				$user->website = $fbUser["website"];
				$user->location_name = $fbUser["location"]["name"];
				$user->access_token = $_SERVER["ATTRIBUTES"]["facebook"]->getAccessToken();
				$fbPicture = $_SERVER["ATTRIBUTES"]["facebook"]->api("/".$fbUser["id"]."?fields=picture");
				if(isset($fbPicture["picture"]["data"]) && isset($fbPicture["picture"]["data"]["url"])){
					$user->picture_url = $fbPicture["picture"]["data"]["url"];
				}else{
					$user->picture_url = "";
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
			$_SERVER["ATTRIBUTES"][$params->get("result", "target")] = $user;
		}
	}
}
