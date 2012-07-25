<?php
/**
 * ポイント付与ルールのモデル
 */
class Member_PointRuleModel extends DatabaseModel{
	const RULE_ENTRY = "entry";
	const RULE_FIRST_ORDER = "first_order";
	const RULE_WELCOME = "welcome";
	const RULE_ORDER_SALES = "order_sales";
	const RULE_TOTAL_SALES = "total_sales";
	
	public function __construct($values = array()){
		$loader = new PluginLoader("Member");
		parent::__construct($loader->loadTable("PointRulesTable"), $values);
	}
	
	public function getRuleNames(){
		$ruleNames = array();
		$ruleNames[Member_PointRuleModel::RULE_ENTRY] = "会員登録時";
		$ruleNames[Member_PointRuleModel::RULE_FIRST_ORDER] = "初回購入時";
		$ruleNames[Member_PointRuleModel::RULE_WELCOME] = "来店時";
		$ruleNames[Member_PointRuleModel::RULE_ORDER_SALES] = "購入時";
		$ruleNames[Member_PointRuleModel::RULE_TOTAL_SALES] = "累積売上到達時";
		return $ruleNames;
	}
	
	public function getRuleName($rule){
		$ruleNames = $this->getRuleNames();
		return $ruleNames[$rule];
	}
	
	public function findByPrimaryKey($point_rule_id){
		$this->findBy(array("point_rule_id" => $point_rule_id));
	}
	
	public function findAllByRule($point_rule, $point_rule_min = null, $point_rule_max = null, $order = "", $reverse = false){
		$condition = array("point_rule" => $point_rule);
		if($point_rule_min != null){
			$condition["point_rule_min"] = $point_rule_min;
		}
		if($point_rule_max != null){
			$condition["point_rule_max"] = $point_rule_max;
		}
		$condition["le:point_rule_start_time:0000-01-01 00:00:00"] = date("Y-m-d H:i:s");
		$condition["ge:point_rule_end_time:9999-12-31 23:59:59"] = date("Y-m-d H:i:s");
		return $this->findAllBy($condition, $order, $reverse);
	}
	
	public function findByActiveRule($point_rule, $point_rule_min = null, $point_rule_max = null){
		$rules = $this->findAllByRule($point_rule, $point_rule_min, $point_rule_max, "point_rule_start_time", true);
		$this->values = $this->values_org = $rules[0]->toArray();
	}
	
	public function getRuleTitle($point_rule, $point_rule_value = null, $point_rule_value_pre = null){
		$condition = array("point_rule" => $point_rule);
		if($point_rule_value_pre != null){
			$condition["gt:point_rule_min:0"] = $point_rule_value_pre;
		}
		if($point_rule_value != null){
			$condition["le:point_rule_min:0"] = $point_rule_value;
			$condition["ge:point_rule_max:999999999"] = $point_rule_value;
		}
		$condition["le:point_rule_start_time:0000-01-01 00:00:00"] = date("Y-m-d H:i:s");
		$condition["ge:point_rule_end_time:9999-12-31 23:59:59"] = date("Y-m-d H:i:s");
		$result = $this->findAllBy($condition, array("point_rule_min", "point_rule_start_time"), array(true, true));

		if(is_array($result) && count($result) > 0){
			$data = $result[0];
			return $data->point_rule_name;
		}
		return "";
	}

	public function getAddPoint($point_rule, $point_rule_value = null, $point_rule_value_pre = null){
		$condition = array("point_rule" => $point_rule);
		if($point_rule_value_pre != null){
			$condition["gt:point_rule_min:0"] = $point_rule_value_pre;
		}
		if($point_rule_value != null){
			$condition["le:point_rule_min:0"] = $point_rule_value;
			$condition["ge:point_rule_max:999999999"] = $point_rule_value;
		}
		$condition["le:point_rule_start_time:0000-01-01 00:00:00"] = date("Y-m-d H:i:s");
		$condition["ge:point_rule_end_time:9999-12-31 23:59:59"] = date("Y-m-d H:i:s");
		$result = $this->findAllBy($condition, array("point_rule_min", "point_rule_start_time"), array(true, true));

		$point = 0;
		if(is_array($result) && count($result) > 0){
			$data = $result[0];
			if($point_rule_value != null){
				$point += floor($point_rule_value * $data->add_point_rate / 100);
			}
			$point += $data->add_point;
		}
		return $point;
	}
	
	
	public function isAddPointDelay($point_rule, $point_rule_value = null, $point_rule_value_pre = null){
		$condition = array("point_rule" => $point_rule);
		if($point_rule_value_pre != null){
			$condition["gt:point_rule_min:0"] = $point_rule_value_pre;
		}
		if($point_rule_value != null){
			$condition["le:point_rule_min:0"] = $point_rule_value;
			$condition["ge:point_rule_max:999999999"] = $point_rule_value;
		}
		$condition["le:point_rule_start_time:0000-01-01 00:00:00"] = date("Y-m-d H:i:s");
		$condition["ge:point_rule_end_time:9999-12-31 23:59:59"] = date("Y-m-d H:i:s");
		$result = $this->findAllBy($condition, array("point_rule_min", "point_rule_start_time"), array(true, true));
		
		$pointDelay = false;
		if(is_array($result) && count($result) > 0){
			$data = $result[0];
			if($data->point_delay_flg == "1"){
				$pointDelay = true;
			}
		}
		return $pointDelay;
	}
	
}
?>