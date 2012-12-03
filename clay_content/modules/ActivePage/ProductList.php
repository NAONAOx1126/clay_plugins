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
			
			if($_SERVER["QUERY_STRING"] == ""){
				$_POST["page"] = 1;
			}

			// ショップデータを検索する。
			if($_SERVER["CLIENT_DEVICE"]->isFuturePhone()){
				$table = $loader->LoadTable("ActiveMobilePagesTable");
			}else{
				$table = $loader->LoadTable("ActivePagesTable");
			}
			$count = new Clay_Query_Select($table);
			$count->addColumn("COUNT(*)", "count");

			$select = new Clay_Query_Select($table);
			$select->addColumn($table->product_code)->addColumn($table->product_name)->addColumn($table->image_url);
			$select->addColumn($table->maker_name)->addColumn($table->price)->addColumn($table->description);
			$select->addOrder($table->create_time, true);
			$select->setLimit($params->get("items", 30), ($_POST["page"] - 1) * $params->get("items", 30));
			
			// データキャッシュを取得
			if(!empty($_POST["category1"]) && !empty($_POST["category2"]) && !empty($_POST["category3"])){
				// 商品一覧
				$count->addWhere($table->category1." = ?", array($_POST["category1"]));
				$count->addWhere($table->category2." = ?", array($_POST["category2"]));
				$count->addWhere($table->category3." = ?", array($_POST["category3"]));
				$result->products_count = $count->execute();
				$select->addWhere($table->category1." = ?", array($_POST["category1"]));
				$select->addWhere($table->category2." = ?", array($_POST["category2"]));
				$select->addWhere($table->category3." = ?", array($_POST["category3"]));
				$result->products = $select->execute();
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")."_pages"] = ceil($result->products_count[0]["count"] / $params->get("items", 30));
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $result->products;
			}elseif(!empty($_POST["category1"]) && !empty($_POST["category2"])){
				// 小カテゴリ
				$count->addWhere($table->category1." = ?", array($_POST["category1"]));
				$count->addWhere($table->category2." = ?", array($_POST["category2"]));
				$count->addWhere($table->category3." = ''");
				$result->products_count = $count->execute();
				$select->addWhere($table->category1." = ?", array($_POST["category1"]));
				$select->addWhere($table->category2." = ?", array($_POST["category2"]));
				$select->addWhere($table->category3." = ''");
				$result->products = $select->execute();
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")."_pages"] = ceil($result->products_count[0]["count"] / $params->get("items", 30));
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $result->products;
			}elseif(!empty($_POST["category1"])){
				// 大カテゴリ用
				$count->addWhere($table->category1." = ?", array($_POST["category1"]));
				$count->addWhere($table->category2." = ''");
				$count->addWhere($table->category3." = ''");
				$result->products_count = $count->execute();
				$select->addWhere($table->category1." = ?", array($_POST["category1"]));
				$select->addWhere($table->category2." = ''");
				$select->addWhere($table->category3." = ''");
				$result->products = $select->execute();
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")."_pages"] = ceil($result->products_count[0]["count"] / $params->get("items", 30));
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $result->products;
			}else{
				// トップページ用
				$count->addWhere($table->category1." = ''");
				$count->addWhere($table->category2." = ''");
				$count->addWhere($table->category3." = ''");
				$result->products_count = $count->execute();
				$select->addWhere($table->category1." = ''");
				$select->addWhere($table->category2." = ''");
				$select->addWhere($table->category3." = ''");
				$result->products = $select->execute();
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")."_pages"] = ceil($result->products_count[0]["count"] / $params->get("items", 30));
				$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $result->products;
			}
		}
	}
}
?>
