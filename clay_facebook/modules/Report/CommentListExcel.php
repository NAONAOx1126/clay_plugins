<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */

/**
 * ### Base.Company.List
 * サイトデータのリストを取得する。
 */
class Facebook_Report_CommentListExcel extends Clay_Plugin_Module_List{
	function execute($params){
			// データ一括取得のため、処理期限を無効化
		ini_set("max_execution_time", 0);
		
		$excel = new PHPExcel();
		$excel->setActiveSheetIndex(0);
		if($params->check("key")){
			// 表の開始位置を指定
			$startCol = 1;
			$startRow = 1;
			
			// フォントを設定
			$excel->getActiveSheet()->getDefaultStyle()->getFont()->setName(mb_convert_encoding('ＭＳ Ｐ ゴシック','cp932'));
			$excel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11);
			
			// 行の高さを設定
			$excel->getActiveSheet()->getRowDimension($startRow)->setRowHeight(30);
			$excel->getActiveSheet()->getRowDimension($startRow + 1)->setRowHeight(50);
			$excel->getActiveSheet()->getRowDimension($startRow + 2)->setRowHeight(30);
			$excel->getActiveSheet()->getRowDimension($startRow + 3)->setRowHeight(30);
			$excel->getActiveSheet()->getRowDimension($startRow + 4)->setRowHeight(30);
				
			// タイトル行を作成
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol))->setWidth(20);
			$excel->getActiveSheet()->getStyle($this->getColKey($startCol).$startRow.":".$this->getColKey($startCol).($startRow + 4))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCFF');
			$excel->getActiveSheet()->getStyle($this->getColKey($startCol).$startRow.":".$this->getColKey($startCol).($startRow + 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$excel->getActiveSheet()->getCell($this->getColKey($startCol).$startRow)->setValue("氏名");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + 1))->setValue("写真");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + 2))->setValue("性別");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + 3))->setValue("年齢");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + 4))->setValue("居住地");
			
			// データ行を作成
			$line = 1;
			foreach($_SERVER["ATTRIBUTES"][$params->get("key")] as $user){
				$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + $line))->setWidth(20);
				$excel->getActiveSheet()->getStyle($this->getColKey($startCol).$startRow.":".$this->getColKey($startCol).($startRow + 4 + count($user["comments"])))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCFF');
				$excel->getActiveSheet()->getStyle($this->getColKey($startCol).$startRow.":".$this->getColKey($startCol).($startRow + 4 + count($user["comments"])))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$excel->getActiveSheet()->getStyle($this->getColKey($startCol + $line).$startRow.":".$this->getColKey($startCol + $line).($startRow + 4 + count($user["comments"])))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + $line).$startRow)->setValue($user["name"]);

				// 画像のオブジェクトを取得する
				if($user["picture_url"] != ""){
					// 画像をローカルに落とす
					$imageRoot = $_SERVER["CONFIGURE"]->site_home."/upload/facebook_images/";
					if(!file_exists($imageRoot)){
						mkdir($imageRoot, 0777, true);
					}
					if(($fp = fopen($imageRoot.$user["facebook_id"].".jpg", "w+")) !== FALSE){
						fwrite($fp, file_get_contents($user["picture_url"]));
						fclose($fp);
					}
					if(file_exists($imageRoot.$user["facebook_id"].".jpg")){
						$image = new PHPExcel_Worksheet_Drawing();
						$image->setName($user["name"]);
						$image->setPath($imageRoot.$user["facebook_id"].".jpg");
						$image->setCoordinates($this->getColKey($startCol + $line).($startRow + 1));
						$image->setOffsetX("40");
						$image->setOffsetY("10");
						$image->setWorksheet($excel->getActiveSheet());
					}
				}
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + $line).($startRow + 2))->setValue(($user["gender"] == "male")?"男":"女");
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + $line).($startRow + 3))->setValue($user["age"]."歳");
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + $line).($startRow + 4))->setValue($user["location_name"]);
				$index = 5;
				foreach($user["comments"] as $date => $comment){
					$excel->getActiveSheet()->getRowDimension($startRow + $index)->setRowHeight(100);
					$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + $index))->setValue($date);
					$message = "";
					foreach($comment as $time => $data){
						$message .= "【".$time."】\n".$data."\n";
					}
					$excel->getActiveSheet()->getCell($this->getColKey($startCol + $line).($startRow + $index))->setValue($message);
					$excel->getActiveSheet()->getStyle($this->getColKey($startCol + $line).($startRow + $index))->getAlignment()->setWrapText(true);
					$index ++;
				}
				$line ++;
			}
			
			$excel->getActiveSheet()->setTitle("発言録");

			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="comment_report.xls"');
			header('Cache-Control: max-age=0');
			
			$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
			$objWriter->save('php://output');
			ob_start();
		}
	}

	private function getColKey($index){
		$source = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if($index - 1 < 26){
			return substr($source, $index - 1, 1);
		}else{
			return substr($source, floor(($index - 1) / 26), 1).substr($source, ($index - 1) % 26, 1);
		}
	}
}
