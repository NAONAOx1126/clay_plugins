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
 * ### Admin.Login
 * Facebookのログイン処理を実行し、ユーザーの情報を更新する。
 * 
 */
class Facebook_Login extends Clay_Plugin_Module{
	function execute($params){
		if($_SERVER["CONFIGURE"]->facebook){
			// FacebookのCURLオプションを変更
			Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
			Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;		
			
			// Facebookのインスタンスを初期化
			$facebook = new Facebook($_SERVER["CONFIGURE"]->facebook);

			// アクセストークンを取得
			$accessToken = $facebook->getAccessToken();
		
			// ユーザーIDを取得
			$uid = $facebook->getUser();
		
			// ユーザーIDが取得できなかった場合には認証ページへ遷移させる。
			if (!$uid) {
				$loginUrl = $facebook->getLoginUrl(array(
					"client_id" => $params->get("appid", $_SERVER["CONFIGURE"]->facebook["appId"]), 
					"canvas" => 1, 
					"fbconnect" => 0, 
					"scope" => implode(",", $_SERVER["CONFIGURE"]->facebook["permissions"]),
					"redirect_uri" => $params->get("url", $_SERVER["CONFIGURE"]->facebook["protocol"].$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])
					));
				// アプリ未登録ユーザーなら facebook の認証ページへ遷移
				echo "<script type='text/javascript'>location.href = '".$loginUrl."';</script>";
				// echo $loginUrl;
				exit;
			}

			// アプリの認証が済んでいる場合には、Facebookのインスタンスとログインユーザーの情報をグローバルに格納
			$_SERVER["ATTRIBUTES"]["facebook"] = $facebook;
		}
	}
}
