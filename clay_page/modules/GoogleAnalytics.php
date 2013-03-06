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
 * ### Page.GoogleAnalytics
 * モバイル用GoogleAnylticのURLを取得するためのモジュールです。
 *
 * @param uid GoogleAnalyticsのID
 */
class Page_GoogleAnalytics extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("uid")){
			// ユーザーIDをパラメータから取得する。
			$uid = $params->get("uid");
			
			// URL構築に必要な変数の定義
			$baseUrl = CLAY_SUBDIR."/ga.php";
			$referer = $_SERVER["HTTP_REFERER"];
			if (empty($referer)) {
			  $referer = "-";
			}
			$query = $_SERVER["QUERY_STRING"];
			$path = $_SERVER["REQUEST_URI"];
			if (!empty($path)) {
			  $url .= "&utmp=" . urlencode($path);
			}
			
			// URLを生成
			$url = "";
			$url .= $baseUrl . "?";
			$url .= "utmac=" . $uid;
			$url .= "&utmn=" . rand(0, 0x7fffffff);
			$url .= "&utmr=" . urlencode($referer);
			$url .= "&guid=ON";
			
			// 結果を変数に格納
			$_SERVER["ATTRIBUTES"]["GA_URL"] = str_replace("&", "&amp;", $url);
		}
	}
}
?>
