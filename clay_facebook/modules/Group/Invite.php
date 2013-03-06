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
class Facebook_Group_Invite extends Clay_Plugin_Module{
	function execute($params){
		// targetにIDが指定されている場合にはそのIDを、keyにターゲット用のキーが
		// 設定されている場合にはPOSTからそのキーで取得する。
		$targetKey = $params->get("key", "");
		$targetId = $params->get("target", $_POST[$targetKey]);
		
		if(!empty($targetId)){
			// グループの情報を取得
			$loader = new Clay_Plugin("facebook");
			$loader->LoadSetting();
			$group = $loader->loadModel("GroupModel");
			$group->findByFacebookId($targetId);
			
			// 自分のアカウントを取得
			$fbUser = $_SERVER["ATTRIBUTES"]["facebook"]->api("/me");
							
			// Facebookのインスタンスを初期化
			$fbParams = array("appId" => $_SERVER["CONFIGURE"]->facebook["appId"], "secret" => $_SERVER["CONFIGURE"]->facebook["secret"]);
			$facebook = new Facebook($fbParams);
			$facebook->setAccessToken($group->access_token);

			// グループの情報を取得する。
			$fbGroup = $facebook->api("/".$targetId);
			
			// グループのオーナーの情報を取得する。
			echo "/".$targetId."/members/".$fbUser["id"];
			$result = $facebook->api("/".$targetId."/members", "post", array("member" => $fbUser["id"]));
			print_r($result);
		}
	}
}
