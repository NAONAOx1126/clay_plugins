<?php
/**
 * ### Product.Developer.Save
 * 開発会社を登録する。
 */
class Product_Developer_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Product");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin("product");
			
			try{
				// POSTされたデータを元にモデルを作成
				$developer = $loader->loadModel("ProductDeveloperModel");
				$developer->findByPrimaryKey($_POST["developer_id"]);
				
				// データを設定
				$developer->developer_name = $_POST["developer_name"];
				$developer->sort_order = $_POST["sort_order"];
				
				// カテゴリを保存
				$developer->save();
						
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("product");
				
				unset($_POST["save"]);
			}catch(Exception $e){
				DBFactory::rollback("product");
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
