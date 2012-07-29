<?php
class Member_SuggestProduct{
	public function execute(){
		// 商品プラグインの初期化
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		$welcome = $loader->loadModel("WelcomeModel");
		$welcome->findByPrimaryKey($_POST["welcome_id"]);

		$result = array();
		if($welcome->welcome_id > 0){
			// 既に登録されている場合はデータを取得する
			$welcomeSuggest = $loader->loadModel("WelcomeSuggestModel");
			$welcomeSuggest->findByWelcomeProduct($welcome->welcome_id, $_POST["product_id"]);
			
			// データの登録
			$welcomeSuggest->customer_id = $welcome->customer_id;
			$welcomeSuggest->welcome_id = $welcome->welcome_id;
			$welcomeSuggest->product_id = $_POST["product_id"];
			
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
			
			$welcomeSuggest = $loader->loadModel("WelcomeSuggestModel");
			$welcomeSuggests = $welcomeSuggest->findAllByWelcome($welcome->welcome_id);
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
