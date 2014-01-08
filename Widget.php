<?php

namespace mdm\easyui;

use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Description of Widget
 *
 * @author MDMunir
 */
class Widget extends yii\base\Widget
{

	public $tag;
	public $options = [];
	public $htmlOptions = [];
	public $plugin;

	public function init()
	{
		ob_start();
		ob_implicit_flush(false);
		parent::init();
	}

	public function run()
	{
		$content = ob_get_clean();

		if (!isset($this->htmlOptions['id'])) {
			$id = $this->htmlOptions['id'] = $this->getId();
		} else {
			$id = $this->id = $this->htmlOptions['id'];
		}
		$options = $this->getClientOptions();
		$options = empty($options) ? '' : Json::encode($options);
		echo Html::tag($this->tag, $content, $this->htmlOptions);
		$view = $this->getView();
		EasyuiAssets::register($view);
	}

	protected function getClientOptions()
	{
		return $this->options;
	}

}