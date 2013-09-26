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
 * ### Form.Submit
 * フォームの登録処理を実行する。
 */
class Form_Submit extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("sheet") && isset($_POST["save"])){
			$sheet_id = $params->get("sheet");
			
			// トランザクションの開始
			Clay_Database_Factory::begin("form");
			Clay_Database_Factory::begin("member");
				
			try{
				// ユーザー情報を登録
				$loader = new Clay_Plugin("Member");
				$loader->LoadSetting();
				$customer = $loader->LoadModel("CustomerModel");
				$customer->findBy(array("email" => $email));
				$customer->email = $_POST["email"];
				$customer->sex = $_POST["sex"];
				$customer->job = $_POST["job"];
				$customer->pref = $_POST["pref"];
				$customer->comment1 = $_POST["comment1"];
				$customer->comment2 = $_POST["comment2"];
				$customer->comment3 = $_POST["comment3"];
				$customer->save();
				
				// 回答票情報を登録
				$loader = new Clay_Plugin("Form");
				$loader->LoadSetting();
				$answer = $loader->LoadModel("AnswerModel");
				$answer->findByCustomerSheet($customer->customer_id, $sheet_id);
				if(!($answer->answer_id > 0)){
					$answer->customer_id = $customer->customer_id;
					$answer->sheet_id = $sheet_id;
					$answer->email = $customer->email;
					$answer->save();
				}
				$answer_id = $answer->answer_id;
				
				// 回答を登録する。
				$this->saveSimpleSelect($answer_id, $sheet_id, "q01");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q02");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q03");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q03b");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q04", "q04b");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q05", "q05b");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q06");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q07", "q07c");
				$this->saveInputText($answer_id, $sheet_id, "q07b");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q08", "q08c");
				$this->saveInputText($answer_id, $sheet_id, "q08b");
				$this->saveInputText($answer_id, $sheet_id, "q09");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q10");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q10b");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q11", "q11b");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q12", "q12b");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q13");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q14", "q14c");
				$this->saveInputText($answer_id, $sheet_id, "q14b");
				$this->saveInputText($answer_id, $sheet_id, "q15");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q16");
				$this->saveInputText($answer_id, $sheet_id, "q17");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q18", "q18c");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q18b", "q18d");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q19");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q20");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q21");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q22");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q23");
				$this->saveInputText($answer_id, $sheet_id, "q24");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q25", "q25c");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q26");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q27");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q28");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q29", "q29c");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q30");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q31");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q32", "q32c");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q33");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q34");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q35");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q35b");
				$this->saveInputText($answer_id, $sheet_id, "q35c");
				foreach($_POST["q36b"] as $index => $name){
					if(!empty($_POST["q36b"][$index]) && !empty($_POST["q36c"][$index])){
						$_POST["q36b"][$index] = $_POST["q36b"][$index]."(".$_POST["q36c"][$index].")";
					}else{
						$_POST["q36b"][$index] = $_POST["q36b"][$index].$_POST["q36c"][$index];
					}
				}
				$_POST["q36b"] = implode("\r\n", $_POST["q36b"]);
				$this->saveSimpleSelect($answer_id, $sheet_id, "q36", "q36b");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q37");
				$_POST["q38"] = implode("\r\n", $_POST["q38"]);
				$this->saveInputText($answer_id, $sheet_id, "q38");
				$_POST["q38b"] = implode("\r\n", $_POST["q38b"]);
				$this->saveInputText($answer_id, $sheet_id, "q38b");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q39");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q40");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q41");
				$this->saveMultipleSelect($answer_id, $sheet_id, "q42");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q43");
				$this->saveInputText($answer_id, $sheet_id, "q44");
				$this->saveSimpleSelect($answer_id, $sheet_id, "q45", "q45c");
				$this->saveInputText($answer_id, $sheet_id, "q46");
				
				unset($_POST["save"]);
				
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit("form");
				Clay_Database_Factory::commit("member");
				$_POST = array();
			}catch(Exception $e){
				Clay_Database_Factory::rollback("form");
				Clay_Database_Factory::rollback("member");
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
	
	private function saveInputText($answer_id, $sheet_id, $question_code){
		$question_id = $this->saveQuestion($sheet_id, $question_code);
		$answerText = $_POST[$question_code];
		$this->saveAnswerDetail($question_id, $answer_id, null, $answerText);
	}
	
	private function saveSimpleSelect($answer_id, $sheet_id, $question_code, $comment_question_code = null){
		$question_id = $this->saveQuestion($sheet_id, $question_code);
		
		$selectText = $_POST[$question_code];
		$question_select_id = $this->saveQuestionSelect($question_id, $selectText);
		
		if($selectText == "その他"){
			$answerText = $_POST[$comment_question_code];
		}else{
			$answerText = "";
		}
		$this->saveAnswerDetail($question_id, $answer_id, $question_select_id, $answerText);
	}

	private function saveMultipleSelect($answer_id, $sheet_id, $question_code, $comment_question_code = null){
		$question_id = $this->saveQuestion($sheet_id, $question_code);
		
		if(is_array($_POST[$question_code])){
			$answerText = $_POST[$question_code]["comment"];
			unset($_POST[$question_code]["comment"]);
			if(!empty($answerText)){
				$_POST[$question_code][] = "その他";
			}
			foreach($_POST[$question_code] as $selectText){
				$question_select_id = $this->saveQuestionSelect($question_id, $selectText);
				$externalText = "";
				if($comment_question_code != null){
					$externalText = $_POST[$comment_question_code][$selectText];
				}
				if($selectText == "その他"){
					$this->saveAnswerDetail($question_id, $answer_id, $question_select_id, $answerText, $externalText);
				}else{
					$this->saveAnswerDetail($question_id, $answer_id, $question_select_id, "", $externalText);
				}
			}
		}
	}
	
	private function saveQuestion($sheet_id, $question_code){
		$loader = new Clay_Plugin("Form");
		$loader->LoadSetting();
		// 設問を取得
		$question = $loader->loadModel("QuestionModel");
		$question->findBySheetCode($sheet_id, $question_code);
		if(!($question->question_id > 0)){
			$question->sheet_id = $sheet_id;
			$question->question_code = $question_code;
			$question->save();
		}
		return $question->question_id;
	}
	
	private function saveQuestionSelect($question_id, $question_select){
		$loader = new Clay_Plugin("Form");
		$loader->LoadSetting();
		// 選択肢を取得
		$questionSelect = $loader->loadModel("QuestionSelectModel");
		$questionSelect->findByQuestionSelect($question_id, $question_select);
		if(!($questionSelect->question_select_id > 0)){
			$questionSelect->question_id = $question_id;
			$questionSelect->question_select = $question_select;
			$questionSelect->save();
		}
		return $questionSelect->question_select_id;
	}
	
	private function saveAnswerDetail($question_id, $answer_id, $question_select_id = null, $answer_text = "", $external_text = ""){
		$loader = new Clay_Plugin("Form");
		$loader->LoadSetting();
		// 回答詳細を登録
		$answerDetail = $loader->loadModel("AnswerDetailModel");
		$answerDetail->findByQuestionAnswer($question_id, $answer_id, $question_select_id);
		if(!($answerDetail->answer_detail_id > 0)){
			$answerDetail->question_id = $question_id;
			$answerDetail->answer_id = $answer_id;
			$answerDetail->question_select_id = $question_select_id;
			$answerDetail->answer_text = $answer_text;
			$answerDetail->external_text = $external_text;
			$answerDetail->save();
		}
	}
}
