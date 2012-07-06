<?php
/**
 * ### Order.Save
 * 注文のデータを登録する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Order_Save extends FrameworkModule{
	function execute($params){
		if(isset($_POST["save"])){
			// 商品情報を登録する。
			$loader = new PluginLoader("Order");
			$loader->LoadSetting();
	
			// トランザクションの開始
			DBFactory::begin("order");
		
			try{
				// 商品データを検索する。
				$order = $loader->LoadModel("OrderModel");
				if(!empty($_POST["order_id"])){
					$order->findByPrimaryKey($_POST["order_id"]);
				}
				
				// 商品データをモデルに格納して保存する。
				foreach($_POST as $key => $value){
					$order->$key = $value;
				}
				
				$order->save();
				$_POST["order_id"] = $order->order_id;
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("order");
			}catch(Exception $e){
				DBFactory::rollback("order");
				throw $e;
			}
		}
	}
}
?>
