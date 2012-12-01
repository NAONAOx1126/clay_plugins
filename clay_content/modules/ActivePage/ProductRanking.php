<?php
/**
 * ### Content.ActivePage.ShopList
 * アクティブページのショップリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_ProductRanking extends Clay_Plugin_Module{
	function execute($params){
		if($shops->shops == ""){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// ショップデータを検索する。
			$table = $loader->LoadTable("ActivePagesTable");
			$select = new Clay_Query_Select($table);
			
			if(!($_POST["page"] > 0)){
				$_POST["page"] = 1;
			}

			// データキャッシュを取得
			if(!empty($_POST["category1"]) && !empty($_POST["category2"]) && !empty($_POST["category3"])){
				// 商品一覧
				$table = $loader->LoadTable("ActivePagesTable");
				$select = new Clay_Query_Select($table);
				$select->addColumn($table->category1)->addColumn($table->category2)->addColumn($table->category3);
				$select->addColumn($table->product_code)->addColumn($table->product_name)->addColumn($table->image_url);
				$select->addColumn($table->maker_name)->addColumn($table->price)->addColumn($table->description);
				$select->addWhere($table->category1." = ?", array($_POST["category1"]));
				$select->addWhere($table->category2." = ?", array($_POST["category2"]));
				$select->addWhere($table->category3." = ?", array($_POST["category3"]));
				$select->addOrder($table->access_count, true);
				$select->setLimit($params->get("items", 3));
				$result->products = $select->execute();
				$_SERVER["ATTRIBUTES"][$params->get("result", "rankings")] = $result->products;
			}elseif(!empty($_POST["category1"]) && !empty($_POST["category2"])){
				// 小カテゴリ
				$table = $loader->LoadTable("ActivePagesTable");
				$select = new Clay_Query_Select($table);
				$select->addColumn($table->category1)->addColumn($table->category2)->addColumn($table->category3);
				$select->addColumn($table->product_code)->addColumn($table->product_name)->addColumn($table->image_url);
				$select->addColumn($table->maker_name)->addColumn($table->price)->addColumn($table->description);
				$select->addWhere($table->category1." = ?", array($_POST["category1"]));
				$select->addWhere($table->category2." = ?", array($_POST["category2"]));
				$select->addOrder($table->access_count, true);
				$select->setLimit($params->get("items", 3));
				$result->products = $select->execute();
				$_SERVER["ATTRIBUTES"][$params->get("result", "rankings")] = $result->products;
			}elseif(!empty($_POST["category1"])){
				// 大カテゴリ用
				$table = $loader->LoadTable("ActivePagesTable");
				$select = new Clay_Query_Select($table);
				$select->addColumn($table->category1)->addColumn($table->category2)->addColumn($table->category3);
				$select->addColumn($table->product_code)->addColumn($table->product_name)->addColumn($table->image_url);
				$select->addColumn($table->maker_name)->addColumn($table->price)->addColumn($table->description);
				$select->addWhere($table->category1." = ?", array($_POST["category1"]));
				$select->addOrder($table->access_count, true);
				$select->setLimit($params->get("items", 3));
				$result->products = $select->execute();
				$_SERVER["ATTRIBUTES"][$params->get("result", "rankings")] = $result->products;
			}else{
				// トップページ用
				$table = $loader->LoadTable("ActivePagesTable");
				$select = new Clay_Query_Select($table);
				$select->addColumn($table->category1)->addColumn($table->category2)->addColumn($table->category3);
				$select->addColumn($table->product_code)->addColumn($table->product_name)->addColumn($table->image_url);
				$select->addColumn($table->maker_name)->addColumn($table->price)->addColumn($table->description);
				$select->addOrder($table->access_count, true);
				$select->setLimit($params->get("items", 3));
				$result->products = $select->execute();
				$_SERVER["ATTRIBUTES"][$params->get("result", "rankings")] = $result->products;
			}
		}
	}
}
?>
