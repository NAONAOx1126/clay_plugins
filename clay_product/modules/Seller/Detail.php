<?php
/**
 * ### Shopping.Seller.Detail
 * 開発会社の詳細情報を取得する。
 * @param type 抽出するカテゴリのタイプ（指定しない場合は全タイプから抽出）
 * @param result 結果を設定する配列のキーワード
 */
class Product_Seller_Detail extends Clay_Plugin_Module{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new Clay_Plugin("Product");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$seller = $loader->LoadModel("ProductSellerModel");
		$seller->findByPrimaryKey($_POST["seller_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "seller")] = $seller;
	}
}
?>
