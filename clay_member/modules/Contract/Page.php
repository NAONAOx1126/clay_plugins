<?php
/**
 * ### Member.Contract.List
 * 契約のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param type 抽出するカテゴリのタイプ（指定しない場合は全タイプから抽出）
 * @param result 結果を設定する配列のキーワード
 */
class Member_Contract_Page extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Member");
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
		
		// カテゴリデータを検索する。
		$contract = $loader->LoadModel("ContractModel");
		$option["totalItems"] = $category->countBy(array());
		$pager = AdvancedPager::factory($option);
		list($from, $to) = $pager->getOffsetByPageId();
		$contract->limit($option["perPage"], $from - 1);
		$contracts = $contract->findAllBy(array());
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "contracts")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "contracts")] = $contracts;
	}
}
?>