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
class Facebook_Report_UserListExcel extends Clay_Plugin_Module_List{
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
			
			// 列の幅を設定
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 1))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 2))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 3))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 4))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 5))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 6))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 7))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 8))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 9))->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension($this->getColKey($startCol + 10))->setWidth(20);
				
			// タイトル行を作成
			$excel->getActiveSheet()->getRowDimension($startRow)->setRowHeight(30);
			$excel->getActiveSheet()->getStyle($this->getColKey($startCol).$startRow.":".$this->getColKey($startCol + 10).$startRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCFF');
			$excel->getActiveSheet()->getStyle($this->getColKey($startCol).$startRow.":".$this->getColKey($startCol + 10).$startRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$excel->getActiveSheet()->getCell($this->getColKey($startCol).$startRow)->setValue("氏名");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 1).$startRow)->setValue("写真");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 2).$startRow)->setValue("性別");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 3).$startRow)->setValue("年齢");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 4).$startRow)->setValue("居住地");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 5).$startRow)->setValue("最終発言日");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 6).$startRow)->setValue("最終いいね日");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 7).$startRow)->setValue("コメント件数");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 8).$startRow)->setValue("いいね件数");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 9).$startRow)->setValue("総コメント件数");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 10).$startRow)->setValue("総いいね件数");
			
			// データ行を作成
			$line = 1;
			foreach($_SERVER["ATTRIBUTES"][$params->get("key")] as $user){
				$excel->getActiveSheet()->getRowDimension($startRow + $line)->setRowHeight(50);
				$excel->getActiveSheet()->getStyle($this->getColKey($startCol).($startRow + $line).":".$this->getColKey($startCol + 10).($startRow + $line))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + $line))->setValue($user->name);

				// 画像のオブジェクトを取得する
				if($user->picture_url != ""){
					// 画像をローカルに落とす
					$imageRoot = $_SERVER["CONFIGURE"]->site_home."/upload/facebook_images/";
					if(!file_exists($imageRoot)){
						mkdir($imageRoot, 0777, true);
					}
					if(($fp = fopen($imageRoot.$user->facebook_id.".jpg", "w+")) !== FALSE){
						fwrite($fp, file_get_contents($user->picture_url));
						fclose($fp);
					}
					if(file_exists($imageRoot.$user->facebook_id.".jpg")){
						$image = new PHPExcel_Worksheet_Drawing();
						$image->setName($user->name);
						$image->setPath($imageRoot.$user->facebook_id.".jpg");
						$image->setCoordinates($this->getColKey($startCol + 1).($startRow + $line));
						$image->setOffsetX("40");
						$image->setOffsetY("10");
						$image->setWorksheet($excel->getActiveSheet());
					}
				}
				
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 2).($startRow + $line))->setValue(($user->gender == "male")?"男":"女");
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 3).($startRow + $line))->setValue($user->age."歳");
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 4).($startRow + $line))->setValue($user->location_name);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 5).($startRow + $line))->setValue(($user->last_comment_time != "")?date("Y/m/d", strtotime($user->last_comment_time)):"");
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 6).($startRow + $line))->setValue(($user->last_like_time != "")?date("Y/m/d", strtotime($user->last_like_time)):"");
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 7).($startRow + $line))->setValue($user->comment_count);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 8).($startRow + $line))->setValue($user->like_count);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 9).($startRow + $line))->setValue(count($user->comments()));
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 10).($startRow + $line))->setValue(count($user->likes()));
				$line ++;
			}
			
			$excel->getActiveSheet()->setTitle("参加者一覧・参加状況一覧");
			
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="user_report.xls"');
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
