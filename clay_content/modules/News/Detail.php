<?php
/**
 * ### Content.News.Detail
 * 新着情報の詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_News_Detail extends Clay_Plugin_Module{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new Clay_Plugin("Content");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$news = $loader->LoadModel("NewsModel");
		$news->findByPrimaryKey($_POST["news_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "news")] = $news;
	}
}
?>
