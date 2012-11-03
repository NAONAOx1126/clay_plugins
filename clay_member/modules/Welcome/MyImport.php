<?php
/**
 * ### Member.Customer.MyImport
 * 自分の商品のリストを取得する。
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Member_Welcome_MyImport extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		if($_SESSION["OPERATOR"]["operator_id"] > 0 && is_object($_SERVER["ATTRIBUTES"]["events"])){
			foreach($_SERVER["ATTRIBUTES"]["events"] as $event){
				if(preg_match("/^[^0-9]+([0-9]+)[^0-9]+/", $event->title, $params) > 0){
					$customer_id = $params[1];
					$reserve_start = substr($event->when[0]->startTime, 0, 10)." ".substr($event->when[0]->startTime, 11, 8);
					$reserve_end = substr($event->when[0]->endTime, 0, 10)." ".substr($event->when[0]->endTime, 11, 8);
					$welcome_date = date("Ymd", strtotime($reserve_start));
					$welcome = $loader->loadModel("WelcomeModel");
					$welcome->findByWelcomeCustomer($welcome_date, $customer_id);
					$welcome->welcome_date = $welcome_date;
					$welcome->customer_id = $customer_id;
					$welcome->operator_id = $_SESSION["OPERATOR"]["operator_id"];
					$welcome->reserve_start = $reserve_start;
					$welcome->reserve_end = $reserve_end;
					$welcome->reserve_title = $event->title;
					$welcome->reserve_comment = $event->content;
					if(!($welcome->welcome_id > 0)){
						$welcome->commit_flg = 0;
					}
					
					// トランザクションの開始
					DBFactory::begin("member");
					
					try{
						// 登録データの保存
						$welcome->save();
						
						// エラーが無かった場合、処理をコミットする。
						DBFactory::commit("member");
							
					}catch(Exception $ex){
						DBFactory::rollback("member");
						throw $ex;
					}
				}
			}
		}
	}
}
?>
