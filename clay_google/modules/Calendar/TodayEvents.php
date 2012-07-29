<?php
/**
 * ### Google.Calendar.TodayEvents
 * Googleのカレンダーを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Google_Calendar_TodayEvents extends FrameworkModule{
	function execute($params){
		if(isset($_SERVER["GOOGLE"]["Client"]) && $_SERVER["GOOGLE"]["Client"]->getAccessToken() && isset($_POST["calendar_id"])){
			$events = $_SERVER["GOOGLE"]["Calendar"]->events->listEvents($_POST["calendar_id"], array("orderBy" => "startTime", "singleEvents" => true, "timeMin" => date("Y-m-d")."T00:00:00.000Z", "timeMax" => date("Y-m-d")."T23:59:59.999Z"));
			$_SERVER["ATTRIBUTES"]["events"] = $events["items"];
		}
	}
}
?>
