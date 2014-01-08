<?php

namespace mdm\easyui;

use yii\web\View;

/**
 * Description of EasyuiAssets
 *
 * @author MDMunir
 */
class EasyuiAssets extends \yii\web\AssetBundle
{

	//put your code here
	public $sourcePath = '@mdm/easyui/assets';
	public $js = [
		'jquery.easyui.min.js',
	];
	public $css = [
		'themes/default/easyui.css',
		'themes/icon.css',
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
	
	/**
	 * @param \yii\web\View $view
	 * @return \yii\web\AssetBundle the registered asset bundle instance
	 */
	public static function register($view)
	{
		$ab = parent::register($view);
		$ja = $view->assetBundles['yii\web\JqueryAsset'];
		$ja->js = [$ab->baseUrl.'/jquery-1.7.2.min.js'];
		return $ab;
	}
}