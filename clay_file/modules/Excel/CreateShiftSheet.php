<?php
/**
 * ### File.Excel.CreateShiftSheet
 * Excelのシートを追加するためのクラスです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Modules
 * @package   File
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 * @param sheet Excelファイルに追加するシート名
 * @param titles シートの一覧のタイトル
 * @param columns シートのカラム名
 * @param key シートのデータとして使用する変数キー名
 */
class File_Excel_CreateShiftSheet extends Clay_Plugin_Module{
	function execute($params){
		// データ一括取得のため、処理期限を無効化
		ini_set("max_execution_time", 0);
		
		if(!isset($_SERVER["PHP_EXCEL"])){
			$_SERVER["PHP_EXCEL"] = new PHPExcel();
			$_SERVER["PHP_EXCEL"]->setActiveSheetIndex(0);
		}else{
			$newIndex = $_SERVER["PHP_EXCEL"]->getSheetCount();
			$_SERVER["PHP_EXCEL"]->createSheet($newIndex);
			$_SERVER["PHP_EXCEL"]->setActiveSheetIndex($newIndex);
		}
		if($params->check("key") && $params->check("titles") && $params->check("columns")){
			$list = $_SERVER["ATTRIBUTES"][$params->get("key")];
			$titles = explode(",", $params->get("titles", ""));
			$columns = explode(",", $params->get("columns", ""));
			$skip = $params->get("skip", "0");
			
			$source = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			
			// フォントを設定
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->getDefaultStyle()->getFont()->setName(mb_convert_encoding('ＭＳ Ｐ ゴシック','cp932'));
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11); 
			
			// 配列キーの最上位は対象月のため、前月・当月データを取得
			$lastData = $list[$_POST["last_target"]];
			$currentData = $list[$_POST["target"]];
			unset($list);
			
			$dataSkip = count($titles) - $skip;
			
			// タイトルエリアの罫線を設定
			for($i = 0; $i < $skip + $dataSkip * 3; $i ++){
				for($j = 1; $j < 3; $j ++){
					$cellBorders = $_SERVER["PHP_EXCEL"]->getActiveSheet()->getStyleByColumnAndRow($i, $j)->getBorders();
					if($j == 1){
						$cellBorders->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					}else{
						$cellBorders->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
					if($i == 0){
						$cellBorders->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					}else{
						$cellBorders->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
					$cellBorders->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					if($i == $skip + $dataSkip * 3 - 1){
						$cellBorders->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					}else{
						$cellBorders->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
				}
			}
			
			// 日付エリアを設定
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->mergeCells("A1:".substr($source, $skip - 1, 1)."1");
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->mergeCells(substr($source, $skip, 1)."1:".substr($source, $skip + $dataSkip - 1, 1)."1");
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $skip, 1)."1", $_POST["last_target"]);
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->mergeCells(substr($source, $skip + $dataSkip, 1)."1:".substr($source, $skip + $dataSkip * 2 - 1, 1)."1");
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $skip + $dataSkip, 1)."1", $_POST["target"]);
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->mergeCells(substr($source, $skip + $dataSkip * 2, 1)."1:".substr($source, $skip + $dataSkip * 3 - 1, 1)."1");
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $skip + $dataSkip * 2, 1)."1", "前月比");
			
			// タイトルエリアを設定する。
			for($i = 0;$i < $skip + $dataSkip * 3; $i ++){
				if($i < $skip + $dataSkip){
					$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $i, 1)."2", $titles[$i]);
				}elseif($i < $skip + $dataSkip * 2){
					$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $i, 1)."2", $titles[$i - $dataSkip]);
				}else{
					$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $i, 1)."2", $titles[$i - $dataSkip * 2]);
				}
			}
			
			foreach($currentData as $index => $value){
				for($i = 0; $i < $skip + $dataSkip * 3; $i ++){
					$value2 = $lastData[$index];
					if($i < $skip){
						$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $i, 1).($index + 3), $value[$columns[$i]]);
					}elseif($i < $skip + $dataSkip){
						$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $i, 1).($index + 3), $value2[$columns[$i]]);
					}elseif($i < $skip + $dataSkip * 2){
						$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $i, 1).($index + 3), $value[$columns[$i - $dataSkip]]);
					}else{
						if($value2[$columns[$i - $dataSkip * 2]] > 0){
							$rate = number_format(($value[$columns[$i - $dataSkip * 2]] - $value2[$columns[$i - $dataSkip * 2]]) / $value2[$columns[$i - $dataSkip * 2]] * 100, 2)."%";
						}else{
							$rate = "100%";
						}
						$_SERVER["PHP_EXCEL"]->getActiveSheet()->setCellValue(substr($source, $i, 1).($index + 3), $rate);
					}
					$cellBorders = $_SERVER["PHP_EXCEL"]->getActiveSheet()->getStyleByColumnAndRow($i, $index + 3)->getBorders();
					$cellBorders->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					if($i == 0){
						$cellBorders->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					}else{
						$cellBorders->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
					if($index < count($currentData) - 1){
						$cellBorders->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}else{
						$cellBorders->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					}
					if($i == $skip + $dataSkip * 3 - 1){
						$cellBorders->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
					}else{
						$cellBorders->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					}
				}
			}
		}
		if($params->check("sheet")){
			$_SERVER["PHP_EXCEL"]->getActiveSheet()->setTitle($params->get("sheet", "test"));
		}
	}
}
?>
