<?php
/**
 * ### Content.ActivePage.ShopList
 * アクティブページのショップリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_Product extends Clay_Plugin_Module{
	function execute($params){
		if($shops->shops == ""){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// ショップデータを検索する。
			$activePage = $loader->LoadTable("ActivePageModel");
			$activePage->findByPrimaryKey($_POST["entry_id"]);

			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// カテゴリを保存
				$activePage->access_count ++;
				$activePage->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
			}catch(Exception $e){
				Clay_Database_Factory::rollback();
			}
			
			$_SERVER["ATTRIBUTES"][$params->get("result", "product")."_key"] = $activePage->key()->toArray();
			$_SERVER["ATTRIBUTES"][$params->get("result", "product")] = $activePage->toArray();
		}
	}
}
?>
