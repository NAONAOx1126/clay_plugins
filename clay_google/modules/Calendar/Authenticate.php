<?php
/**
 * ### Google.Calendar.Authenticate
 * Googleのカレンダーの予定一覧を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Google_Calendar_Authenticate extends FrameworkModule{
	function execute($params){
		// クライアントライブラリをインクルード
		require_once FRAMEWORK_PLUGIN_HOME.'/clay_google/common/apiClient.php';
		// カレンダーライブラリをインクルード
		require_once FRAMEWORK_PLUGIN_HOME.'/clay_google/common/contrib/apiCalendarService.php';
		
		// クライアントを初期化
		$_SERVER["GOOGLE"]["Client"] = new apiClient();
		$_SERVER["GOOGLE"]["Client"]->setApplicationName("Google Calendar PHP Starter Application");
		$_SERVER["GOOGLE"]["Client"]->setClientId($params->get("client_id"));
		$_SERVER["GOOGLE"]["Client"]->setClientSecret($params->get("client_secret"));
		$_SERVER["GOOGLE"]["Client"]->setRedirectUri($params->get("redirect_uri"));
		
		// カレンダーを初期化
		$_SERVER["GOOGLE"]["Calendar"] = new apiCalendarService($_SERVER["GOOGLE"]["Client"]);
		
		// ログアウト処理
		if (isset($_GET['logout'])) {
		  unset($_SESSION[GOOGLE_OAUTH_TOKEN_KEY]);
		  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['FRAMEWORK_URL_BASE'].$_SERVER["TEMPLATE_NAME"]);
		}
		
		// 認証を行う。
		if (!isset($_SESSION[GOOGLE_OAUTH_TOKEN_KEY]) && isset($_GET['code'])) {
		  $_SERVER["GOOGLE"]["Client"]->authenticate();
		  $_SESSION[GOOGLE_OAUTH_TOKEN_KEY] = $_SERVER["GOOGLE"]["Client"]->getAccessToken();
		  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['FRAMEWORK_URL_BASE'].$_SERVER["TEMPLATE_NAME"]);
		}
		
		// クライアントにアクセストークンを追加
		if (isset($_SESSION[GOOGLE_OAUTH_TOKEN_KEY])) {
		  $_SERVER["GOOGLE"]["Client"]->setAccessToken($_SESSION[GOOGLE_OAUTH_TOKEN_KEY]);
		}
		
		// アクセストークンが取得できなかった場合は認証URLにリダイレクト
		if (!$_SERVER["GOOGLE"]["Client"]->getAccessToken()) {
			header("Location: ".$_SERVER["GOOGLE"]["Client"]->createAuthUrl());
			exit;
		}
	}
}
?>
