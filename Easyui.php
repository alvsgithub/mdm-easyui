<?php

namespace mdm\easyui;

use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Description of Html
 *
 * @author MDMunir
 */
class Easyui
{

	//put your code here
	public static $plugins;
	public static $stack = [];
	public static $counter = 0;

	public static function __callStatic($plugin, $arguments)
	{
		$plugin = strtolower($plugin);
		$options = ArrayHelper::getValue($arguments, 0, []);
		$view = ArrayHelper::getValue($arguments, 1, \Yii::$app->view);
		if (strpos($plugin, 'begin') === 0) {
			$plugin = substr($plugin, 5);
			array_unshift($arguments, $plugin);
			return call_user_func_array([get_called_class(), 'begin'], $arguments);
		} elseif (strpos($plugin, 'end') === 0) {
			$plugin = substr($plugin, 3);
			array_unshift($arguments, $plugin);
			return call_user_func_array([get_called_class(), 'end'], $arguments);
		} else {
			array_unshift($arguments, $plugin);
			return call_user_func_array([get_called_class(), 'plugin'], $arguments);
		}
	}

	public static function plugin($plugin, $options, $view = null, $content = '')
	{
		if (self::$plugins === null) {
			self::$plugins = require(__DIR__ . '/plugins.php');
		}
		$tag = ArrayHelper::remove($options, 'tag', self::$plugins[$plugin]['tag']);
		$htmlOptions = ArrayHelper::remove($options, 'htmlOptions', []);
		if (!isset($htmlOptions['id'])) {
			$htmlOptions['id'] = self::getId();
		}
		$id = $htmlOptions['id'];

		if (isset(self::$plugins[$plugin]['beforeContent'])) {
			$beforeContent = self::$plugins[$plugin]['beforeContent'];
			if (is_string($beforeContent)) {
				$beforeContent = [get_called_class(), $beforeContent];
			}
			$content = call_user_func_array($beforeContent, [&$options]) . $content;
		}
		if (isset(self::$plugins[$plugin]['afterContent'])) {
			$afterContent = self::$plugins[$plugin]['afterContent'];
			if (is_string($afterContent)) {
				$afterContent = [get_called_class(), $afterContent];
			}
			$content .= call_user_func_array($afterContent, [&$options]);
		}

		$result = '';
		if (isset(self::$plugins[$plugin]['beforePlugin'])) {
			$beforePlugin = self::$plugins[$plugin]['beforePlugin'];
			if (is_string($beforePlugin)) {
				$beforePlugin = [get_called_class(), $beforePlugin];
			}
			$result = call_user_func_array($beforePlugin, [&$options]);
		}
		$result .= Html::tag($tag, $content, $htmlOptions);
		if (isset(self::$plugins[$plugin]['afterPlugin'])) {
			$afterPlugin = self::$plugins[$plugin]['afterPlugin'];
			if (is_string($afterPlugin)) {
				$afterPlugin = [get_called_class(), $afterPlugin];
			}
			$result .= call_user_func_array($afterPlugin, [&$options]);
		}

		$options = empty($options) ? '' : Json::encode($options);
		$js = "jQuery('#{$id}').{$plugin}($options);";
		if ($view === null) {
			$view = \Yii::$app->view;
		}
		EasyuiAssets::register($view);
		$view->registerJs($js);

		return $result;
	}

	public static function begin($plugin, $options, $view = null, $content = '')
	{
		self::$stack[] = [$plugin, $options, $view];
		ob_start();
		ob_implicit_flush(false);
		echo $content;
	}

	public static function end($plugin = null)
	{
		if (!empty(self::$stack)) {
			$end = array_pop(self::$stack);
			if ($plugin !== null && $plugin != $end[0]) {
				throw new InvalidCallException("Expecting end{$end[0]}() , found end{$plugin}())");
			}
			list($plugin, $options, $view) = $end;
		} else {
			throw new InvalidCallException("Unexpected end{$plugin}() call. A matching begin{$plugin}() is not found.");
		}
		$content = ob_get_clean();
		return self::plugin($plugin, $options, $view, $content);
	}

	public static function getId()
	{
		return 'e' . self::$counter++;
	}

	protected static function regionPanel(&$options)
	{
		$result = [];
		$items = ArrayHelper::remove($options, 'regions', []);
		foreach ($items as $region => $panel) {
			if (is_string($panel)) {
				$panel = ['content' => $panel];
			}
			$panel['region'] = $region;
			$content = ArrayHelper::remove($panel, 'content', '');
			$htmlOptions = ArrayHelper::remove($panel, 'htmlOptions', []);
			$tag = ArrayHelper::remove($panel, 'tag', 'div');
			if (isset($panel['href'])) {
				$panel['href'] = Html::url($panel['href']);
			}
			$htmlOptions['id'] = ArrayHelper::remove($panel, 'id');
			$htmlOptions['data-options'] = self::encode($panel);
			$result[] = Html::tag($tag, $content, $htmlOptions);
		}
		return implode("\n", $result);
	}

	protected static function accordionPanel(&$options)
	{
		$result = [];
		$items = ArrayHelper::remove($options, 'items', []);
		foreach ($items as $i => $panel) {
			if (is_string($panel)) {
				$panel = ['content' => $panel];
			}
			$content = ArrayHelper::remove($panel, 'content', '');
			$htmlOptions = ArrayHelper::remove($panel, 'htmlOptions', []);
			$tag = ArrayHelper::remove($panel, 'tag', 'div');
			if (isset($panel['href'])) {
				$panel['href'] = Html::url($panel['href']);
			}
			$htmlOptions['title'] = ArrayHelper::remove($panel, 'title', '');
			$htmlOptions['id'] = ArrayHelper::remove($panel, 'id');
			$htmlOptions['data-options'] = self::encode($panel);
			$result[] = Html::tag($tag, $content, $htmlOptions);
		}
		return implode("\n", $result);
	}

	protected static function tabsPanel(&$options)
	{
		$result = [];
		$items = ArrayHelper::remove($options, 'tabs', []);
		foreach ($items as $i => $panel) {
			if (is_string($panel)) {
				$panel = ['content' => $panel];
			}
			$content = ArrayHelper::remove($panel, 'content', '');
			$htmlOptions = ArrayHelper::remove($panel, 'htmlOptions', []);
			$tag = ArrayHelper::remove($panel, 'tag', 'div');
			if (isset($panel['href'])) {
				$panel['href'] = Html::url($panel['href']);
			}
			$htmlOptions['title'] = ArrayHelper::remove($panel, 'title', '');
			$htmlOptions['id'] = ArrayHelper::remove($panel, 'id');
			$htmlOptions['data-options'] = self::encode($panel);
			$result[] = Html::tag($tag, $content, $htmlOptions);
		}
		return implode("\n", $result);
	}

	protected static function menuItem(&$options)
	{
		$result = [];
		$items = ArrayHelper::remove($options, 'items', []);
		foreach ($items as $i => $panel) {
			if (is_string($panel)) {
				$panel = ['content' => $panel];
			}
			$content = ArrayHelper::remove($panel, 'label', '');
			$htmlOptions = ArrayHelper::remove($panel, 'htmlOptions', []);
			if (isset($panel['href'])) {
				$panel['href'] = Html::url($panel['href']);
			}
			$tag = ArrayHelper::remove($panel, 'tag', 'div');
			if (!empty($panel['items'])) {
				$itemOptions = ArrayHelper::remove($panel, 'itemOptions', []);
				$children = Html::tag('div', self::menuItem($panel), $itemOptions);
				$htmlOptions['data-options'] = self::encode($panel);
				$content = Html::tag($tag, $content, $htmlOptions) . "\n" . $children;
				$result[] = Html::tag('div', $content);
			} else {
				$htmlOptions['data-options'] = self::encode($panel);
				$result[] = Html::tag($tag, $content, $htmlOptions);
			}
		}
		return implode("\n", $result);
	}

	protected static function createMenu(&$options)
	{
		static $counter = 1;
		if (is_string($options['menu'])) {
			return;
		}
		$menu['items'] = ArrayHelper::remove($options, 'menu', []);
		$htmlOptions = ArrayHelper::remove($options, 'menuOptions', []);
		if (!isset($htmlOptions['id'])) {
			$htmlOptions['id'] = 'e_menu' . $counter++;
		}
		$options['menu'] = '#' . $htmlOptions['id'];
		return Html::tag($tag, self::menuItem($menu), $htmlOptions);
	}

	public static function encode($value)
	{
		if ($value === []) {
			return null;
		}
		$result = Json::encode($value);
		$result = substr($result, 1);
		return substr($result, 0, strlen($result) - 1);
	}

}