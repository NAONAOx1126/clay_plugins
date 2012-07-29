<?php
/**
 * ### Google.Calendar.Detail
 * Googleのカレンダーを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Google_Calendar_Detail extends FrameworkModule{
	function execute($params){
		if(isset($_SERVER["GOOGLE"]["Client"]) && $_SERVER["GOOGLE"]["Client"]->getAccessToken()){
			$_SERVER["ATTRIBUTES"]["calendar"] = $_SERVER["GOOGLE"]["Calendar"]->calendars->get("naonao3939@gmail.com");
		}
	}
}
?>
