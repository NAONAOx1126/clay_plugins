<?php
/**
 * ポイントログのモデルクラス
 */
class Member_PointLogModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Member");
		parent::__construct($loader->loadTable("PointLogsTable"), $values);
	}
	
	public function findByPrimaryKey($point_log_id){
		$this->findBy(array("point_log_id" => $point_log_id));
	}
	
	public function findAllByCustomer($customer_id, $order = "", $reverse = false){
		return $this->findAllBy(array("customer_id" => $customer_id), $order, $reverse);
	}
	
	public function customer(){
		$loader = new PluginLoader("Member");
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($this->customer_id);
		return $customer;
	}
	
	public function add($customer_id, $point, $comment = "", $commit = true){
		$this->log_time = date("Y-m-d H:i:s");
		$this->customer_id = $customer_id;
		$this->point = $point;
		$this->comment = $comment;
		if($commit){
			$this->commit_flg = 1;
		}else{
			$this->commit_flg = 0;
		}
		if($this->customer_id > 0 && $this->point != 0){
			parent::create();
		}
	}
	
	public function addCustomer($customer_id, $point, $comment = "", $commit = true){
		$this->log_time = date("Y-m-d H:i:s");
		$this->customer_id = $customer_id;
		$this->point = $point;
		$this->comment = $comment;
		if($commit){
			$this->commit_flg = 1;
		}else{
			$this->commit_flg = 0;
		}
		if($this->customer_id > 0 && $this->point != 0){
			parent::create();
		}
	}
	
	public function addRuledPoint($rule, $ruleType, $ruleValue = null, $ruleValuePre = null){
		$customer_id = ($_SESSION[CUSTOMER_SESSION_KEY]["customer_id"] > 0)?$_SESSION[CUSTOMER_SESSION_KEY]["customer_id"]:$_POST["customer_id"];
		$this->addCustomerRuledPoint($customer_id, $rule, $ruleType, $ruleValue, $ruleValuePre);
	}
	
	public function addCustomerRuledPoint($customer_id, $rule, $ruleType, $ruleValue = null, $ruleValuePre = null, $comment = ""){
		// ルールからポイント情報を取得
		if(!empty($comment)){
			$title = $comment;
		}else{
			$title = $rule->getRuleTitle($ruleType, $ruleValue, $ruleValuePre);
		}
		$point = $rule->getAddPoint($ruleType, $ruleValue, $ruleValuePre);
		$pointDelay = $rule->isAddPointDelay($ruleType, $ruleValue, $ruleValuePre);
		
		// ユーザー情報を取得
		$this->customer_id = $customer_id;
		$customer = $this->customer();
		
		// ポイントが0の場合は処理をスキップ
		if($point != 0){
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// 即時反映の時はポイントデータを加算
				if(!$pointDelay && $customer->customer_id > 0){
					// タイプ設定を追加した場合、ポイントを追加する。
					$customer->point += $point;
					
					// 変更内容をデータベースに反映
					$customer->save();
				}
				
				$this->add($customer_id, $point, $title, !$pointDelay);
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
					
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
		}
	}
}
?>