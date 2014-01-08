<?php

return [
	// form
	'validatebox' => [
		'tag' => 'input',
	],
	'combobox' => [
		'tag' => 'input',
	],
	'combogrid' => [
		'tag' => 'input',
	],
	'numberbox' => [
		'tag' => 'input',
	],
	'datebox' => [
		'tag' => 'input',
	],
	'datetimebox' => [
		'tag' => 'input',
	],
	'spinner' => [
		'tag' => 'input',
	],
	'numberspinner' => [
		'tag' => 'input',
	],
	'timespinner' => [
		'tag' => 'input',
	],
	'slider' => [
		'tag' => 'input',
	],
	// panel
	'draggable' => [
		'tag' => 'div',
	],
	'droppable' => [
		'tag' => 'div',
	],
	'resizable' => [
		'tag' => 'div',
	],
	'panel' => [
		'tag' => 'div',
	],
	'window' => [
		'tag' => 'div',
	],
	'dialog' => [
		'tag' => 'div',
	],
	'calender' => [
		'tag' => 'div',
	],
	// layout
	'layout' => [
		'tag' => 'div',
		'beforeContent' => 'regionPanel',
	],
	'accordion' => [
		'tag' => 'div',
		'beforeContent' => 'accordionPanel',
	],
	'tabs' => [
		'tag' => 'div',
		'beforeContent' => 'tabsPanel',
	],
	// menu
	'menu' => [
		'tag' => 'div',
		'beforeContent' => 'menuItem',
	],
	'searchbox' => [
		'tag' => 'input',
		'beforeContent' => 'createMenu',
	],
	'menubutton' => [
		'tag' => 'div',
		'afterPlugin' => 'createMenu',
	],
	'splitbutton' => [
		'tag' => 'div',
		'afterPlugin' => 'createMenu',
	],
];