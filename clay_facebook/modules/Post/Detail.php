<?php
/**
 * ### Member.Customer.Detail
 * 顧客の詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Facebook_Post_Detail extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Facebook");
		$loader->LoadSetting();

		// 商品データを検索する。
		$post = $loader->LoadModel("PostModel");
		$post->findByPrimaryKey($_POST["post_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "post")] = $post;
	}
}
?>
