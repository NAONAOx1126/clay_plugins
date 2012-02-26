<?php
/**
 * ### Order.Summery.SliceRepeat
 * 受注データでのサマリを取得する。
 * @param title タイトルに相当するカラムを指定
 * @param summery サマリーに相当するカラムを指定
 * @param result 結果を設定する配列のキーワード
 */
class Order_Summery_SliceRepeat extends FrameworkModule{
	function execute($params){
		if($params->check("key")){
			// まず、リストを前月と当月のデータに絞り込む
			$list = $_SERVER["ATTRIBUTES"][$params->get("key")];
			$lastData = $list[$_POST["last_target"]];
			$currentData = $list[$_POST["target"]];
			unset($list);
			
			// 結果を格納する配列を初期化
			$result = array($_POST["last_target"] => array(), $_POST["target"] => array());
			
			// リピート配列の階層により、データをコピー
			if(isset($currentData[0])){
				foreach($currentData as $key1 => $data1){
					foreach($data1 as $key2 => $data){
						// 対応するデータを取得する。
						$d = array();
						foreach($lastData as $k1 => $d1){
							foreach($d1 as $k2 => $d2){
								if($data["product_code"] == $d2["product_code"] && $key2 == $k2){
									$d = $d2;
								}
							}
						}
						// それぞれ、データを配列内に設定する。
						$d["rank"] = $data["rank"] = $key1 + 1;
						$d["repeat"] = $data["repeat"] = $key2;
						$result[$_POST["last_target"]][] = $d;
						$result[$_POST["target"]][] = $data;
					}
				}
			}else{
				foreach($currentData as $key1 => $data){
					// 対応するデータを取得する。
					$d = array();
					foreach($lastData as $k1 => $d1){
						if($key1 == $k1){
							$d = $d1;
						}
					}
					// それぞれ、データを配列内に設定する。
					$d["repeat"] = $data["repeat"] = $key1;
					$result[$_POST["last_target"]][] = $d;
					$result[$_POST["target"]][] = $data;
				}
			}
			
			$_SERVER["ATTRIBUTES"][$params->get("key")] = $result;
		}
	}
}
?>
