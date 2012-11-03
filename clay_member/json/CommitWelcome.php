<?php
class Member_CommitWelcome{
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		$welcome = $loader->loadModel("WelcomeModel");
		$welcome->findByPrimaryKey($_POST["welcome_id"]);
		
		$result = array();
		if($welcome->welcome_id > 0){
			if($welcome->commit_flg == "0"){
				// トランザクションの開始
				DBFactory::begin("member");
				
				try{
					$rule = $loader->loadModel("PointRuleModel");
						
					// 新規登録時は登録ポイントを登録
					$customer = $welcome->customer();
					$pointLog = $loader->loadModel("PointLogModel");
					$pointLog->addCustomerRuledPoint($welcome->customer_id, $rule, Member_PointRuleModel::RULE_WELCOME);
					
					// 来店処理した日時を設定
					$welcome->commit_flg = "1";
					$welcome->commit_time = date("Y-m-d H:i:s");
				
					// 登録データの保存
					$welcome->save();
					
					// エラーが無かった場合、処理をコミットする。
					DBFactory::commit("member");
						
				}catch(Exception $ex){
					DBFactory::rollback("member");
					throw $ex;
				}
			}
			// 結果を設定
			$result = $welcome->toArray();
		}
		return $result;
	}
}
?>
