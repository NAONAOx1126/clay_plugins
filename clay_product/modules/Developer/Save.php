<?php
/**
 * ### Product.Developer.Save
 * 開発会社を登録する。
 */
class Product_Developer_Save extends FrameworkModule{
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
				$developer = $loader->loadModel("ProductDeveloperModel");
				$developer->findByPrimaryKey($_POST["developer_id"]);
				
				// データを設定
				$developer->developer_name = $_POST["developer_name"];
				$developer->sort_order = $_POST["sort_order"];
				
				// カテゴリを保存
				$developer->save($db);
						
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["save"]);
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
