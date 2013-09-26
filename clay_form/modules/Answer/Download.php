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
 * ### Member.Pair.List
 * ペアのリストを取得する。
 */
class Form_Answer_Download extends Clay_Plugin_Module_List{
	function execute($params){
		$this->executeImpl($params, "Form", "QuestionModel", $params->get("result", "questions"));
		$this->executeImpl($params, "Form", "AnswerModel", $params->get("result", "answers"));
		$questions = array();
		$questionSelects = array();
		foreach($_SERVER["ATTRIBUTES"][$params->get("result", "questions")] as $question){
			$questions[$question->question_id] = $question;
			$selections = $question->questionSelects();
			if(is_array($selections)){
				foreach($selections as $selection){
					$questionSelects[$selection->question_select_id] = $selection;
				}
			}
		}
		$answers = $_SERVER["ATTRIBUTES"][$params->get("result", "answers")];
		
		header("Content-Type: application/csv");
		header("Content-Disposition: attachment; filename=\"hakusho".date("YmdHis").".csv\"");
		
		// ダウンロードの際は、よけいなバッファリングをクリア
		ob_end_clean();
		
		$data = array();
		$data[] = "メールアドレス";
		$data[] = "性別";
		$data[] = "年代";
		$data[] = "職種";
		$data[] = "業種";
		foreach($_POST["columns"] as $question_id){
			$data[] = $questions[$question_id]->question_title."\r\n".$questions[$question_id]->question;
		}
		echo mb_convert_encoding("\"".implode("\",\"", $data)."\"\r\n", "Shift_JIS", "UTF-8");
		
		foreach($answers as $answer){
			$data = array();
			$customer = $answer->customer();
			$data[] = $customer->email;
			$data[] = (($customer->sex == "1")?"男":"女");
			$data[] = $customer->comment1;
			$data[] = $customer->job.(!empty($customer->comment2)?"(".$customer->comment2.")":"");
			$data[] = $customer->customer_type.(!empty($customer->comment3)?"(".$customer->comment3.")":"");
			$detailsTemp = $answer->details();
			$details = array();
			foreach($detailsTemp as $temp){
				if(!is_array($details[$temp->question_id])){
					$details[$temp->question_id] = array();
				}
				$details[$temp->question_id][] = $temp;
			}
			foreach($_POST["columns"] as $question_id){
				$list = array();
				if(is_array($details[$question_id])){
					foreach($details[$question_id] as $item){
						$d = "";
						if($item->question_select_id > 0){
							$d .= $questionSelects[$item->question_select_id]->question_select;
						}
						if(!empty($item->answer_text)){
							$d .= "(".$item->answer_text.")";
						}
						if(!empty($item->external_text)){
							$d .= "[".$item->external_text."]";
						}
						$list[] = $d;
					}
				}
				$data[] = implode("/", $list);
			}
			echo mb_convert_encoding("\"".implode("\",\"", $data)."\"\r\n", "Shift_JIS", "UTF-8");
		}
		exit;
	}
}
