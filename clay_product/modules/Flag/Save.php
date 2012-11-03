<?php
/**
 * ### Product.Flag.Save
 * 商品フラグを登録する。
 */
class Product_Flag_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Product");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin("product");
			
			try{
				// POSTされたデータを元にモデルを作成
				$flag = $loader->loadModel("FlagModel");
				$flag->findByPrimaryKey($_POST["flag_id"]);
				
				// データを設定
				$flag->flag_image = $_POST["flag_image"];
				$flag->flag_image_s = $_POST["flag_image_s"];
				$flag->flag_text = $_POST["flag_text"];
				$flag->sort_order = $_POST["sort_order"];
				
				// カテゴリを保存
				$flag->save();
						
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("product");
				
				unset($_POST["save"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback("product");
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
