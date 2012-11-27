<?php
/**
 * ### Content.ActivePage.Import
 * アクティブページのデータをインポートする。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_Import extends Clay_Plugin_Module{
	function execute($params){
		ini_set("max_execution_time", 0);
		
		// 登録されているカテゴリタイプのリストを取得
		$loader = new Clay_Plugin("Content");
		$loader->LoadSetting();
		
		// トランザクションの開始
		Clay_Database_Factory::begin();
		
		try{
			// ショップIDのデータを全削除
			$table = $loader->LoadTable("ActivePagesTable");
			$delete = new Clay_Query_Delete($table);
			$delete->addWhere($table->shop_id." = ".$_POST["shop_id"])->execute();
			
			// インポートするファイルを読み込む
			$filename = $_SERVER["CONFIGURE"]->site_home."/data/ActiveContents/upload/".$_POST["shop_id"].".csv";
			if(($fp = fopen($filename, "r")) !== FALSE){
				$insert = new Clay_Query_Insert($table);
				while(($data = fgetcsv($fp)) !== FALSE){
					// 1カラム目はshop_idと同じになる
					if($data[0] == $_POST["shop_id"]){
						$sqlval = array();
						$sqlval["shop_id"] = mb_convert_encoding($data[0], "UTF-8", "Shift_JIS");
						$sqlval["shop_name"] = mb_convert_encoding($data[1], "UTF-8", "Shift_JIS");
						$sqlval["product_id"] = mb_convert_encoding($data[2], "UTF-8", "Shift_JIS");
						$sqlval["category1"] = mb_convert_encoding($data[3], "UTF-8", "Shift_JIS");
						$sqlval["category2"] = mb_convert_encoding($data[4], "UTF-8", "Shift_JIS");
						$sqlval["category3"] = mb_convert_encoding($data[5], "UTF-8", "Shift_JIS");
						$sqlval["product_name"] = mb_convert_encoding($data[7], "UTF-8", "Shift_JIS");
						$sqlval["product_code"] = mb_convert_encoding($data[8], "UTF-8", "Shift_JIS");
						$sqlval["jan_code"] = mb_convert_encoding($data[9], "UTF-8", "Shift_JIS");
						$sqlval["maker_name"] = mb_convert_encoding($data[10], "UTF-8", "Shift_JIS");
						$sqlval["image_url"] = mb_convert_encoding($data[11], "UTF-8", "Shift_JIS");
						$sqlval["link_url"] = mb_convert_encoding($data[12], "UTF-8", "Shift_JIS");
						$sqlval["price"] = mb_convert_encoding($data[14], "UTF-8", "Shift_JIS");
						$sqlval["description"] = mb_convert_encoding($data[19], "UTF-8", "Shift_JIS");
						$insert->execute($sqlval);
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
?>
