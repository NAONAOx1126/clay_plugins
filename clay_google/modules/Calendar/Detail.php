<?php
/**
 * ### Google.Calendar.Detail
 * Googleのカレンダーの詳細を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Google_Calendar_Detail extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_SERVER["GOOGLE"]["Client"])){
			// Zendの初期化
			require_once("Zend/Loader.php");
			Zend_Loader::loadClass("Zend_Gdata");
			Zend_Loader::loadClass("Zend_Gdata_Calendar");
			
			$service = new Zend_Gdata_Calendar($_SERVER["GOOGLE"]["Client"]);
			$calendars= $service->getCalendarListFeed();
			$_SERVER["ATTRIBUTES"]["calendars"] = $calendars;
		}
	}
}
?>
