<?php
/**
 * ### Content.Advertise.Start
 * カバー画像を削除する。
 */
class Content_Advertise_Start extends FrameworkModule{
	function execute($params){
		// モデルの初期化
		$loader = new PluginLoader("Content");
		$advertise = $loader->loadModel("AdvertiseModel");
		
		// キーのリストを取得する。
		$keys = array();
		foreach($_POST as $key => $value){
			$keys[$key] = $key;
		}
		
		// 取得したキーと現在日時に該当するレコードを取得する。
		$advertises = $advertise->findAllBy(array("in:advertise_key" => $keys, "le:advertise_start_time" => date("Y-m-d 00:00:00"), "ge:advertise_end_time" => date("Y-m-d 00:00:00")));
		
		// 該当したレコードのうち、キーと値のペアが一致するものがあった場合には、それを対象とする。
		foreach($advertises as $advertise){
			if($_POST[$advertise->advertise_key] == $advertise->advertise_code){
				// cookieにキーとコードを保存する。
				setcookie("advertise_key", $advertise->advertise_key, time() + 2592000);
				setcookie("advertise_code", $advertise->advertise_code, time() + 2592000);
			}
		}
	}
}
?>
