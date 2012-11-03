<?php
/**
 * ### Google.Calendar.TodayEvents
 * Googleのカレンダーを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Google_Calendar_TodayEvents extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_SERVER["GOOGLE"]["Client"]) && isset($_POST["calendar_id"])){
			// Zendの初期化
			require_once("Zend/Loader.php");
			Zend_Loader::loadClass("Zend_Gdata");
			Zend_Loader::loadClass("Zend_Gdata_Calendar");
			
			$service = new Zend_Gdata_Calendar($_SERVER["GOOGLE"]["Client"]);
			$query = $service->newEventQuery();
			$user = substr($_POST["calendar_id"], strrpos($_POST["calendar_id"], "/") + 1);
			$query->setUser($user);
			$query->setProjection("full");
			$query->setVisibility("private");
			$query->setOrderby("starttime");
			$query->setStartMin(date("Y-m-d"));
			$query->setStartMax(date("Y-m-d", strtotime("+1 day")));

			try {
				$events = $service->getCalendarEventFeed($query);
				
				$_SERVER["ATTRIBUTES"]["events"] = $events;
		    } catch (Zend_Gdata_App_Exception $e) {
		        echo "エラー: " . $e->getMessage();
		    }
			/*
			$events = $service->getCalendarEventFeed($query);
			
			$_SERVER["ATTRIBUTES"]["events"] = $events;
			*/
		}
	}
}
?>
