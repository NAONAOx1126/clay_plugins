<?php
/**
 * ### Content.Advertise.Detail
 * 広告の詳細情報を取得する。
 * @param type 抽出するカテゴリのタイプ（指定しない場合は全タイプから抽出）
 * @param result 結果を設定する配列のキーワード
 */
class Content_Advertise_Detail extends FrameworkModule{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new PluginLoader("Content");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$advertise = $loader->LoadModel("AdvertiseModel");
		$advertise->findByPrimaryKey($_POST["advertise_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "advertise")] = $advertise;
	}
}
?>
