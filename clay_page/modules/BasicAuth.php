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
 * ### Page.BasicAuth
 * 簡易的にページ単位のBasic認証を設定するためのモジュールです。
 *
 * @param login Basic認証のログインID
 * @param password Basic認証のパスワード
 * @param text 認証ダイアログのメッセージ
 * @param error エラー時のメッセージ
 */
class Page_BasicAuth extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("login") && $params->check("password")){
			if($_SERVER["PHP_AUTH_USER"] != $params->get("login") || $_SERVER["PHP_AUTH_PW"] != $params->get("password")) {
				header("WWW-Authenticate: Basic realm=\"".$params->get("text", "Please Enter Your Password")."\"");
				header("HTTP/1.0 401 Unauthorized");
				//キャンセル時の表示
				echo $params->get("error", "Authorization Required");
				exit;
			}
		}
	}
}
?>
