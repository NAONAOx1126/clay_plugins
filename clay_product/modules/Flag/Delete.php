<?php
/**
 * ### Product.Flag.Delete
 * 商品フラグを削除する。
 */
class Product_Flag_Delete extends FrameworkModule{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new PluginLoader("Product");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin("product");
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$flag = $loader->loadModel("FlagModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["flag_id"])){
					$_POST["flag_id"] = array($_POST["flag_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["flag_id"] as $flag_id){
					// カテゴリを削除
					$flag->findByPrimaryKey($flag_id);
					$flag->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("product");
				
				unset($_POST["flag_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback("product");
				unset($_POST["flag_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
