<?php
/**
 * ### Product.Seller.Save
 * 開発会社を登録する。
 */
class Product_Seller_Save extends FrameworkModule{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new PluginLoader("Product");
			$loader->LoadSetting();
			
			// トランザクションデータベースの取得
			$db = DBFactory::getConnection("product");
			
			// トランザクションの開始
			$db->beginTransaction();
			
			try{
				// POSTされたデータを元にモデルを作成
				$seller = $loader->loadModel("ProductSellerModel");
				$seller->findByPrimaryKey($_POST["seller_id"]);
				
				// データを設定
				$seller->seller_name = $_POST["seller_name"];
				$seller->sort_order = $_POST["sort_order"];
				
				// カテゴリを保存
				$seller->save($db);
						
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["save"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
