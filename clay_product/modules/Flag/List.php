<?php
/**
 * ### Shopping.Flag.List
 * 商品フラグのリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Product_Flag_List extends FrameworkModule{
	function execute($params){
		// ローダーの初期化
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();
		
		// 並べ替え順序が指定されている場合に適用
		$sortOrder = "";
		$sortReverse = false;
		if($params->check("sort_key")){
			$sortOrder = $_POST[$params->get("sort_key")];
			if(preg_match("/^rev@/", $sortOrder) > 0){
				list($dummy, $sortOrder) = explode("@", $sortOrder);
				$sortReverse = true;
			}
		}
		
		// カテゴリデータを検索する。
		$flag = $loader->LoadModel("FlagModel");
		$flags = $flag->findAllBy(array(), $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "flags")] = $flags;
	}
}
?>
