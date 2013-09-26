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
 * ### Form.Confirm
 * フォームの登録処理を実行する。
 */
class Form_Confirm extends Clay_Plugin_Module{
	private $checkErrors = array();
	
	function execute($params){
		if($params->check("sheet")){
			$sheet_id = $params->get("sheet");
			$this->registered($sheet_id, "email");
			if($_POST["bind_user"] == "1"){
				$this->requireSelect($sheet_id, "q01");
				$this->requireSelect($sheet_id, "q02");
				$this->requireSelect($sheet_id, "q03");
				$this->requireSelect($sheet_id, "q03b");
				$this->requireSelect($sheet_id, "q04");
				$this->requireSelect($sheet_id, "q05");
				$this->requireSelect($sheet_id, "q06");
				$this->requireSelect($sheet_id, "q07");
				$this->requireSelect($sheet_id, "q08");
				$this->requireInput($sheet_id, "q09");
			}
			if($_POST["live_user"] == "1"){
				$this->requireSelect($sheet_id, "q10");
				$this->requireSelect($sheet_id, "q10b");
				$this->requireSelect($sheet_id, "q11");
				$this->requireSelect($sheet_id, "q12");
				$this->requireSelect($sheet_id, "q13");
				$this->requireSelect($sheet_id, "q14");
				$this->requireInput($sheet_id, "q15");
			}
			if($_POST["bind_user"] == "1" && $_POST["live_user"] == "1"){
				$this->requireSelect($sheet_id, "q16");
				$this->requireInput($sheet_id, "q17");
				$this->requireSelect($sheet_id, "q18");
				$this->requireSelect($sheet_id, "q18", "q18b");
			}
			$this->requireSelect($sheet_id, "q19");
			if($_POST["q19"] == "はい"){
				$this->requireSelect($sheet_id, "q20");
				$this->requireSelect($sheet_id, "q21");
				$this->requireSelect($sheet_id, "q22");
				$this->requireSelect($sheet_id, "q23");
				$this->requireInput($sheet_id, "q24");
			}else{
				$this->requireSelect($sheet_id, "q25");
				$this->requireSelect($sheet_id, "q26");
				$this->requireSelect($sheet_id, "q27");
				$this->requireSelect($sheet_id, "q28");
			}
			$this->requireSelect($sheet_id, "q29");
			$this->requireSelect($sheet_id, "q30");
			$this->requireSelect($sheet_id, "q31");
			$this->requireSelect($sheet_id, "q32");
			$this->requireSelect($sheet_id, "q33");
			$this->requireSelect($sheet_id, "q34");
			$this->requireSelect($sheet_id, "q35");
			$this->requireSelect($sheet_id, "q35b");
			$this->requireSelect($sheet_id, "q36");
			$this->requireSelect($sheet_id, "q37");
			$this->requireSelect($sheet_id, "q38");
			$this->requireSelect($sheet_id, "q38b");
			$this->requireSelect($sheet_id, "q39");
			$this->requireSelect($sheet_id, "q40");
			$this->requireSelect($sheet_id, "q41");
			$this->requireSelect($sheet_id, "q42");
			$this->requireSelect($sheet_id, "q43");
			$this->requireInput($sheet_id, "q44");
			$this->requireSelect($sheet_id, "q45");
			$this->requireInput($sheet_id, "q46");
			
			if(!empty($this->checkErrors)){
				throw new Clay_Exception_Invalid($this->checkErrors);
			}
		}
	}
	
	private function registered($sheet_id, $emailKey){
		$loader = new Clay_Plugin("Form");
		$loader->LoadSetting();
		$answer = $loader->LoadModel("AnswerModel");
		$answer->findBy(array("sheet_id" => $sheet_id, "email" => $_POST[$emailKey]));
		if($answer->answer_id > 0){
			// 既に回答済みの場合はエラー
			$this->checkErrors[$emailKey] = "既に回答済みです。";
		}
	}
	
	private function required($sheet_id, $question_code, $target = null){
		$loader = new Clay_Plugin("Form");
		$loader->LoadSetting();
		$question = $loader->LoadModel("QuestionModel");
		if($target == null){
			$target = $question_code;
		}
		if(is_array($_POST[$target])){
			foreach($_POST[$target] as $value){
				if(!empty($value)){
					return "";
				}
			}
			$question->findbySheetCode($sheet_id, $question_code);
			if($question->question_id > 0 && !empty($question->question)){
				return $question->question;
			}else{
				return $question_code;
			}
		}else{
			if(empty($_POST[$target])){
				$question->findbySheetCode($sheet_id, $question_code);
				if($question->question_id > 0 && !empty($question->question)){
					return $question->question;
				}else{
					return $question_code;
				}
			}
		}
		return "";
	}
	
	protected function requireInput($sheet_id, $question_code, $target = null){
		$result = $this->required($sheet_id, $question_code, $target);
		if(!empty($result)){
			$this->checkErrors[$question_code] = "「".$result."」を入力してください。";
		}
	}

	protected function requireSelect($sheet_id, $question_code, $target = null){
		$result = $this->required($sheet_id, $question_code, $target);
		if(!empty($result)){
			$this->checkErrors[$question_code] = "「".$result."」を選択してください。";
		}
	}
}
