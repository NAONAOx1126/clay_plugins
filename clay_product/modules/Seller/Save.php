<?php
/**
 * ### Product.Seller.Save
 * 開発会社を登録する。
 */
class Product_Seller_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Product");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin("product");
			
			try{
				// POSTされたデータを元にモデルを作成
				$seller = $loader->loadModel("ProductSellerModel");
				$seller->findByPrimaryKey($_POST["seller_id"]);
				
				// データを設定
				$seller->seller_name = $_POST["seller_name"];
				$seller->sort_order = $_POST["sort_order"];
				
				// カテゴリを保存
				$seller->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit("product");
				
				unset($_POST["save"]);
			}catch(Exception $e){
				Clay_Database_Factory::rollback("product");
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
