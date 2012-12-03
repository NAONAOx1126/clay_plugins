<?php
/**
 * ### Content.ActivePage.ShopList
 * アクティブページのショップリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_CategoryList extends Clay_Plugin_Module{
	function execute($params){
		if($shops->shops == ""){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// ショップデータを検索する。
			if($_SERVER["CLIENT_DEVICE"]->isFuturePhone()){
				$table = $loader->LoadTable("ActiveMobilePagesTable");
			}else{
				$table = $loader->LoadTable("ActivePagesTable");
			}
			$select = new Clay_Query_Select($table);

			// データキャッシュを取得
			if(!empty($_POST["category1"]) && !empty($_POST["category2"]) && !empty($_POST["category3"])){
				// 商品一覧
				$_SERVER["ATTRIBUTES"]["baseurl"] = "/".$_POST["category1"]."/".$_POST["category2"]."/".$_POST["category3"]."/";
				$_SERVER["ATTRIBUTES"]["current_category"] = $_POST["category3"];
				$_SERVER["ATTRIBUTES"]["category_path"] = array($_POST["category1"], $_POST["category2"]);
				$_SERVER["ATTRIBUTES"][$params->get("result", "categories")] = array();
			}elseif(!empty($_POST["category1"]) && !empty($_POST["category2"])){
				// 小カテゴリ
				$select->addColumn($table->category3, "category")->addColumn("COUNT(".$table->entry_id.")", "count");
				$select->addWhere($table->category1." = ?", array($_POST["category1"]));
				$select->addWhere($table->category2." = ?", array($_POST["category2"]));
				$select->addWhere($table->category3." != ''");
				$select->addGroupBy($table->category3)->addOrder("COUNT(".$table->entry_id.")", true);
				$result->categories = $select->execute();
				$_SERVER["ATTRIBUTES"]["baseurl"] = "/".$_POST["category1"]."/".$_POST["category2"]."/";
				$_SERVER["ATTRIBUTES"]["current_category"] = $_POST["category2"];
				$_SERVER["ATTRIBUTES"]["category_path"] = array($_POST["category1"]);
				$_SERVER["ATTRIBUTES"][$params->get("result", "categories")] = $result->categories;
			}elseif(!empty($_POST["category1"])){
				// 中カテゴリ
				$select->addColumn($table->category2, "category")->addColumn("COUNT(".$table->entry_id.")", "count");
				$select->addWhere($table->category1." = ?", array($_POST["category1"]));
				$select->addWhere($table->category2." != ''");
				$select->addGroupBy($table->category2)->addOrder("COUNT(".$table->entry_id.")", true);
				$result->categories = $select->execute();
				$_SERVER["ATTRIBUTES"]["baseurl"] = "/".$_POST["category1"]."/";
				$_SERVER["ATTRIBUTES"]["current_category"] = $_POST["category1"];
				$_SERVER["ATTRIBUTES"]["category_path"] = array();
				$_SERVER["ATTRIBUTES"][$params->get("result", "categories")] = $result->categories;
			}else{
				// 大カテゴリ
				$select->addColumn($table->category1, "category")->addColumn("COUNT(".$table->entry_id.")", "count");
				$select->addWhere($table->category1." != ''");
				$select->addGroupBy($table->category1)->addOrder("COUNT(".$table->entry_id.")", true);
				$result->categories = $select->execute();
				$_SERVER["ATTRIBUTES"]["baseurl"] = "/";
				$_SERVER["ATTRIBUTES"]["current_category"] = "";
				$_SERVER["ATTRIBUTES"]["category_path"] = array();
				$_SERVER["ATTRIBUTES"][$params->get("result", "categories")] = $result->categories;
			}
		}
	}
}
?>
