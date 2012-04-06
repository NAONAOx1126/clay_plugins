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
			$db = DBFactory::getConnection();
			
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
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				$db->rollBack();
				throw $e;
			}
		}
	}
}
?>
