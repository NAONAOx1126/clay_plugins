<?php
/**
 * ### Content.ActivePage.ShopList
 * アクティブページのショップリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_ProductList extends Clay_Plugin_Module{
	function execute($params){
		if($shops->shops == ""){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// ショップデータを検索する。
			$table = $loader->LoadTable("ActivePagesTable");
			$select = new Clay_Query_Select($table);

			// データキャッシュを取得
			if(!empty($_POST["category1"]) && !empty($_POST["category2"]) && !empty($_POST["category3"])){
				// 商品一覧
			}elseif(!empty($_POST["category1"]) && !empty($_POST["category2"])){
				// 小カテゴリ
			}elseif(!empty($_POST["category1"])){
				// 大カテゴリ用
				// トップページ用
				$key = urlencode($_POST["category1"]);
				$result = Clay_Cache_Factory::create("active_page_category2_".$key);
				if($result->products == ""){
					// カテゴリデータを検索する。
					$table = $loader->LoadTable("ActivePagesTable");
					$select = new Clay_Query_Select($table);
					$select->addColumn($table->product_code)->addColumn($table->product_name);
					$select->addColumn($table->price)->addColumn($table->description);
					$select->addWhere($table->category1." = ?", array($_POST["category1"]));
					$select->addWhere($table->category2." = ''");
					$select->addWhere($table->category3." = ''");
					$select->addOrder($table->create_time, true);
					$select->setLimit($params->get("items", 10), ($_POST["page"] - 1) * $params->get("items", 10));
					$result->products = $select->execute();
				}
				$_SERVER["ATTRIBUTES"]["baseurl"] = $_POST["category1"]."/";
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $result->products;
			}else{
				// トップページ用
				$result = Clay_Cache_Factory::create("active_page_category1");
				if($result->products == ""){
					// カテゴリデータを検索する。
					$table = $loader->LoadTable("ActivePagesTable");
					$select = new Clay_Query_Select($table);
					$select->addColumn($table->product_code)->addColumn($table->product_name);
					$select->addColumn($table->price)->addColumn($table->description);
					$select->addWhere($table->category1." = ''");
					$select->addWhere($table->category2." = ''");
					$select->addWhere($table->category3." = ''");
					$select->addOrder($table->create_time, true);
					$result->products = $select->execute();
				}
				$_SERVER["ATTRIBUTES"]["baseurl"] = "";
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $result->products;
			}
		}
	}
}
?>
