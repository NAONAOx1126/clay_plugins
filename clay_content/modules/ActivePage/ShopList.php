<?php
/**
 * ### Content.ActivePage.ShopList
 * アクティブページのショップリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_ShopList extends Clay_Plugin_Module{
	function execute($params){
		// データキャッシュを取得
		$shops = Clay_Cache_Factory::create("active_page_shops");
		if($shops->shops == ""){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// ショップデータを検索する。
			$table = $loader->LoadTable("ActivePagesTable");
			$select = new Clay_Query_Select($table);
			$select->addColumn($table->shop_id)->addColumn($table->shop_name)->addColumn("COUNT(".$table->entry_id.")", "count");
			$shops->shops = $select->addGroupBy($table->shop_id)->execute();
		}
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "active_page_shops")] = $shops->shops;
	}
}
?>
