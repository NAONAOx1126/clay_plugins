<?php
/**
 * ### Content.ActivePage.Import
 * アクティブページのデータをインポートする。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_ImportAccessTrade extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["access_trade"]) && $_FILES["upload_file"]["error"] == 0){
			ini_set("max_execution_time", 0);
			
			// 登録されているカテゴリタイプのリストを取得
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// インポートするファイルを読み込む
				$table = $loader->LoadTable("ActivePagesTable");
				$filename = $_FILES["upload_file"]["tmp_name"];
				if(($fp = fopen($filename, "r")) !== FALSE){
					$insert = new Clay_Query_Replace($table);
					while(($data = fgetcsv($fp)) !== FALSE){
						// shop_idは必ず数値
						if(preg_match("/^[0-9]+$/", $data[0]) > 0){
							$sqlval = array();
							$sqlval["shop_id"] = mb_convert_encoding($data[0], "UTF-8", "Shift_JIS");
							$sqlval["shop_name"] = mb_convert_encoding($data[1], "UTF-8", "Shift_JIS");
							$sqlval["product_id"] = mb_convert_encoding($data[2], "UTF-8", "Shift_JIS");
							$sqlval["category1"] = mb_convert_encoding($data[3], "UTF-8", "Shift_JIS");
							$sqlval["category2"] = mb_convert_encoding($data[4], "UTF-8", "Shift_JIS");
							$sqlval["category3"] = mb_convert_encoding($data[5], "UTF-8", "Shift_JIS");
							$sqlval["product_name"] = mb_convert_encoding($data[7], "UTF-8", "Shift_JIS");
							$sqlval["product_code"] = mb_convert_encoding((!empty($data[8])?$data[8]:preg_replace("/^".$data[0]."-/", "", $data[2])), "UTF-8", "Shift_JIS");
							$sqlval["jan_code"] = mb_convert_encoding($data[9], "UTF-8", "Shift_JIS");
							$sqlval["maker_name"] = mb_convert_encoding($data[10], "UTF-8", "Shift_JIS");
							$sqlval["image_url"] = mb_convert_encoding($data[11], "UTF-8", "Shift_JIS");
							$sqlval["link_url"] = mb_convert_encoding($data[12], "UTF-8", "Shift_JIS");
							$sqlval["price"] = mb_convert_encoding($data[14], "UTF-8", "Shift_JIS");
							$sqlval["description"] = mb_convert_encoding($data[19], "UTF-8", "Shift_JIS");
							if(!empty($sqlval["product_code"])){
								$insert->execute($sqlval);
							}
						}
					}
				}
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
			}catch(Exception $e){
				Clay_Database_Factory::rollback();
				throw $e;
			}
		}
	}
}
?>
