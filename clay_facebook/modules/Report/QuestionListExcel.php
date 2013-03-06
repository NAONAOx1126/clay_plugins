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
class Facebook_Report_QuestionListExcel extends Clay_Plugin_Module_List{
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
				
			// タイトル行を作成
			$excel->getActiveSheet()->getRowDimension($startRow)->setRowHeight(30);
			$excel->getActiveSheet()->getRowDimension($startRow + 1)->setRowHeight(30);
			$excel->getActiveSheet()->getStyle($this->getColKey($startCol).$startRow.":".$this->getColKey($startCol + 6).($startRow + 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCFF');
			$excel->getActiveSheet()->getStyle($this->getColKey($startCol).$startRow.":".$this->getColKey($startCol + 6).($startRow + 1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$excel->getActiveSheet()->getCell($this->getColKey($startCol).$startRow)->setValue("Wall ID");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 1).$startRow)->setValue("投票開始日");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 2).$startRow)->setValue("選択肢１");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 3).$startRow)->setValue("選択肢２");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 4).$startRow)->setValue("選択肢３");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 5).$startRow)->setValue("選択肢４");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 6).$startRow)->setValue("選択肢５");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + 1))->setValue("テーマ");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 1).($startRow + 1))->setValue("投票終了日");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 2).($startRow + 1))->setValue("回答数");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 3).($startRow + 1))->setValue("回答数");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 4).($startRow + 1))->setValue("回答数");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 5).($startRow + 1))->setValue("回答数");
			$excel->getActiveSheet()->getCell($this->getColKey($startCol + 6).($startRow + 1))->setValue("回答数");
				
			// データ行を作成
			$line = 2;
			foreach($_SERVER["ATTRIBUTES"][$params->get("key")] as $post){
				$excel->getActiveSheet()->getRowDimension($startRow + $line)->setRowHeight(30);
				$excel->getActiveSheet()->getStyle($this->getColKey($startCol).($startRow + $line).":".$this->getColKey($startCol + 6).($startRow + $line))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + $line))->setValue($post->facebook_id);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 1).($startRow + $line))->setValue(date("Y/m/d", strtotime($post->start_time)));
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 2).($startRow + $line))->setValue($post->option1);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 3).($startRow + $line))->setValue($post->option2);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 4).($startRow + $line))->setValue($post->option3);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 5).($startRow + $line))->setValue($post->option4);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 6).($startRow + $line))->setValue($post->option5);
				$line ++;
				$excel->getActiveSheet()->getRowDimension($startRow + $line)->setRowHeight(30);
				$excel->getActiveSheet()->getStyle($this->getColKey($startCol).($startRow + $line).":".$this->getColKey($startCol + 6).($startRow + $line))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol).($startRow + $line))->setValue($post->theme()->theme_name);
				$excel->getActiveSheet()->getCell($this->getColKey($startCol + 1).($startRow + $line))->setValue(date("Y/m/d", strtotime($post->end_time)));
				if($post->option1 != ""){
					$excel->getActiveSheet()->getCell($this->getColKey($startCol + 2).($startRow + $line))->setValue(($post->option1_real)?$post->option1_real:0);
				}
				if($post->option2 != ""){
					$excel->getActiveSheet()->getCell($this->getColKey($startCol + 3).($startRow + $line))->setValue(($post->option2_real)?$post->option2_real:0);
				}
				if($post->option3 != ""){
					$excel->getActiveSheet()->getCell($this->getColKey($startCol + 4).($startRow + $line))->setValue(($post->option3_real)?$post->option3_real:0);
				}
				if($post->option4 != ""){
					$excel->getActiveSheet()->getCell($this->getColKey($startCol + 5).($startRow + $line))->setValue(($post->option4_real)?$post->option4_real:0);
				}
				if($post->option5 != ""){
					$excel->getActiveSheet()->getCell($this->getColKey($startCol + 6).($startRow + $line))->setValue(($post->option5_real)?$post->option5_real:0);
				}
				$line ++;
			}
			
			$excel->getActiveSheet()->setTitle("アンケート結果");
			
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="question_report.xls"');
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
