<?php
/**
 * ### Member.Customer.Page
 * 商品のリストをページング付きで取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Facebook_Report_Download extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("facebook");
		$loader->LoadSetting();

		// 顧客データを検索する。
		$report = $loader->LoadModel("ReportModel");
		$report->findByPrimaryKey($_POST["report_id"]);
		
		if($params->get("admin_role", "1") == $_SERVER["ATTRIBUTES"]["OPERATOR"]->role_id || $_SESSION["OPERATOR"]["company_id"] == $report->company_id){
			$ext = ".txt";
			$type = "report_type".$_POST["file"];
			switch($report->$type){
				case "text/pdf":
					$ext = ".pdf";
					break;
				case "application/excel":
					$ext = ".xls";
					break;
				case "image/jpeg":
				case "image/pjpeg":
					$ext = ".jpg";
					break;
				case "image/gif":
					$ext = ".gif";
					break;
				case "image/png":
					$ext = ".png";
					break;
			}
			$filename = "report".date("Ymd", strtotime($report->report_time))."_".$_POST["file"].$ext;
			// ファイルのダウンロード処理
			$key = "report_file".$_POST["file"];
			header("Content-Type: ".$report->$type);
			header("Content-Disposition: attachment; filename=\"".$filename."\"");
			if(file_exists($_SERVER["CONFIGURE"]->site_home."/upload/facebook_report/".$report->$key)){
				echo file_get_contents($_SERVER["CONFIGURE"]->site_home."/upload/facebook_report/".$report->$key);
			}
			exit;
		}
		throw new Clay_Exception_Invalid(array("このファイルのダウンロードの権限がありません。"));
	}
}
?>
