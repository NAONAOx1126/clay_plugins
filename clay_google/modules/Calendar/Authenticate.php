<?php
/**
 * ### Google.Calendar.Authenticate
 * Googleのカレンダーの予定一覧を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Google_Calendar_Authenticate extends Clay_Plugin_Module{
	function execute($params){
		// Zendの初期化
		require_once("Zend/Loader.php");
		Zend_Loader::loadClass("Zend_Gdata");
		Zend_Loader::loadClass("Zend_Gdata_AuthSub");
		
		// アクセストークンが取得できなかった場合は認証URLにリダイレクト
		if (!isset($_SESSION[GOOGLE_OAUTH_TOKEN_KEY]) && !isset($_GET["token"])) {
			$url = Zend_Gdata_AuthSub::getAuthSubTokenUri("http://".$_SERVER['HTTP_HOST'].CLAY_SUBDIR.$_SERVER["TEMPLATE_NAME"], "http://www.google.com/calendar/feeds/", false, true);
			header("HTTP/1.0 307 Temporary redirect");
			header("Location: ".$url);
			exit;
		}
		
		// 認証を行う。
		if (!isset($_SESSION[GOOGLE_OAUTH_TOKEN_KEY]) && isset($_GET["token"])) {
			$_SESSION[GOOGLE_OAUTH_TOKEN_KEY] = Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET["token"]);
			header("Location: http://".$_SERVER['HTTP_HOST'].CLAY_SUBDIR.$_SERVER["TEMPLATE_NAME"]);
		}
		
		// クライアントにアクセストークンを追加
		if (isset($_SESSION[GOOGLE_OAUTH_TOKEN_KEY])) {
		  $_SERVER["GOOGLE"]["Client"] = Zend_Gdata_AuthSub::getHttpClient($_SESSION[GOOGLE_OAUTH_TOKEN_KEY]);
		}
	}
}
?>
