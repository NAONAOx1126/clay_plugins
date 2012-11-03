<?php
/**
 * ### Content.Cover.Detail
 * カバー画像の詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_Cover_Detail extends Clay_Plugin_Module{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new Clay_Plugin("Content");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$cover = $loader->LoadModel("CoverModel");
		$cover->findByPrimaryKey($_POST["cover_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "cover")] = $cover;
	}
}
?>
