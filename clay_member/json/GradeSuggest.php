<?php
class Member_GradeSuggest{
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		$welcomeSuggest = $loader->loadModel("WelcomeSuggestModel");
		$welcomeSuggest->findByPrimaryKey($_POST["suggest_id"]);
		
		$result = array();
			
		if($welcomeSuggest->suggest_id > 0){
			$welcomeSuggest->grade = $_POST["grade"];
			if(isset($_POST["comment"])){
				$welcomeSuggest->comment = $_POST["comment"];
			}
			
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// 登録データの保存
				$welcomeSuggest->save();
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
					
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
			
			$welcomeSuggests = $welcomeSuggest->findAllByWelcome($welcomeSuggest->welcome_id);
			foreach($welcomeSuggests as $suggest){
				$data = $suggest->product()->toArray();
				$data["suggest_id"] = $suggest->suggest_id;
				$data["welcome_id"] = $suggest->welcome_id;
				$data["grade"] = $suggest->grade;
				$result[] = $data;
			}
		}

		return $result;
	}
}
?>
