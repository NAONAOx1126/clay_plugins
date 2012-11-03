<?php
/**
 * ### Member.Customer.Delete
 * 商品を削除する。
 */
class Member_PointRule_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Member");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$pointRule = $loader->loadModel("PointRuleModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["point_rule_id"])){
					$_POST["point_rule_id"] = array($_POST["point_rule_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["point_rule_id"] as $point_rule_id){
					// カテゴリを削除
					$pointRule->findByPrimaryKey($point_rule_id);
					$pointRule->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
				
				unset($_POST["point_rule_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback("member");
				unset($_POST["category_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
