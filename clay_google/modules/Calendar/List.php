<?php
/**
 * ### Google.Calendar.List
 * Googleのカレンダーの一覧を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Google_Calendar_List extends FrameworkModule{
	function execute($params){
		if(isset($_SERVER["GOOGLE"]["Client"]) && $_SERVER["GOOGLE"]["Client"]->getAccessToken()){
			$calendars = $_SERVER["GOOGLE"]["Calendar"]->calendarList->listCalendarList();
			$_SERVER["ATTRIBUTES"]["calendars"] = $calendars["items"];
		}
	}
}
?>
