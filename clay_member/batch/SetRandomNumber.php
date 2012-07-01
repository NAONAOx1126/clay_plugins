<?php
// パラメータを変数に格納
$randomTo = $argv[0];

// この機能で使用するモデルクラス
$loader = new PluginLoader("Member");
$loader->LoadSetting();

// 全てのデータにランダムな数値を設定する。
$model = $loader->loadModel("CustomerModel");
$result = $model->findAllBy(array());
$customers = array();
foreach($result as $data){
	$customers[] = $data->customer_id;
}
$model = $loader->loadModel("CustomerOptionModel");
$result = $model->findAllByName($randomTo);
$randoms = array();
foreach($result as $data){
	$randoms[$data->customer_id] = $data;
}

foreach($customers as $customer_id){
	// ランダムデータが存在しない場合は新規作成
	if(!isset($randoms[$customer_id])){
		$randoms[$customer_id] = $loader->loadModel("CustomerOptionModel", array("customer_id" => $customer_id, "option_name" => $randomTo));
	}
	
	// Likeカウントを設定
	$randoms[$customer_id]->option_value = mt_rand();
	
	// データを保存
	DBFactory::begin("member");
	try{
		$randoms[$customer_id]->save();
		DBFactory::commit("member");
	}catch(Exception $e){
		DBFactory::rollback("member");
	}
}
?>
