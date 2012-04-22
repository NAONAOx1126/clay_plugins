<?php
/**
 * ### Content.Advertise.List
 * 広告のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param type 抽出するカテゴリのタイプ（指定しない場合は全タイプから抽出）
 * @param result 結果を設定する配列のキーワード
 */
class Content_Advertise_Page extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Content");
		$loader->LoadSetting();

		// ページャのオプションを設定
		$option = array();
		$option["mode"] = "Sliding";		// 現在ページにあわせて表示するページリストをシフトさせる。
		$option["perPage"] = $params->get("item", "10");			// １ページあたりの件数
		$option["delta"] = $params->get("delta", "3");				// 現在ページの前後に表示するページ番号の数（Slidingの場合は2n+1ページ分表示）
		$option["prevImg"] = "<";			// 前のページ用のテキスト
		$option["nextImg"] = ">";			// 次のページ用のテキスト
		$option["prevAccessKey"] = "*";			// 前のページ用のアクセスキー
		$option["nextAccessKey"] = "#";			// 次のページ用のアクセスキー
		$option["firstPageText"] = "<<"; 	// 最初のページ用のテキスト
		$option["lastPageText"] = ">>";		// 最後のページ用のテキスト
		$option["curPageSpanPre"] = "<font color=\"#000000\">";		// 現在ページのプレフィクス
		$option["curPageSpanPost"] = "</font>";		// 現在ページのサフィックス
		$option["clearIfVoid"] = false;			// １ページのみの場合のページリンクの出力の有無
		
		// 検索条件と並べ替えキー以外を無効化する。
		if($params->get("clear", "0") == "1"){
			if($params->check("sort_key")){
				$_POST = array("search" => array(), $params->get("sort_key") => $_POST[$params->get("sort_key")]);
			}else{
				$_POST = array("search" => array());
			}
		}
		
		// カテゴリデータを検索する。
		$advertise = $loader->LoadModel("AdvertiseModel");
		$option["totalItems"] = $advertise->countBy(array());
		$pager = AdvancedPager::factory($option);
		list($from, $to) = $pager->getOffsetByPageId();
		$advertise->limit($option["perPage"], $from - 1);
		$advertises = $advertise->findAllBy(array());
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "advertises")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "advertises")] = $advertises;
	}
}
?>
