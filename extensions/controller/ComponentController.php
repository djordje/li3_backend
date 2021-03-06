<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_backend\extensions\controller;

use lithium\action\Controller;
use lithium\core\Libraries;

/**
 * Class ComponentController is base for all controllers that should have access to backend
 * templates. This class enables you to use `'backend'` and `'partial'` layouts if you specify
 * `_viewAs` param or use `'default'` layout.
 * If your action have `'backend_'` prefix controller will be automatically configured to use
 * `'backend'` layout.
 */
class ComponentController extends Controller {

	/**
	 * Define controller global backend view
	 *
	 * @var bool|string `false`, `'partial-component'`, `'backend-component'`
	 */
	protected $_viewAs = false;

	protected function _init() {
		parent::_init();
		if ($this->_viewAs) {
			$this->_viewAs($this->_viewAs);
		} elseif (isset($this->request->params['backend']) && $this->request->params['backend']) {
			$this->_viewAs('backend-component');
		}
	}

	/**
	 * Define what backend view you want to use
	 * You should use `'partial-component'` template for components that should not have
	 * links to other backend components, for eg. _login_ or _user registration_.
	 * For pure backend stuff you should use `'backend-component'`, this view is used by default
	 * for all actions prefixed with `backend_`.
	 *
	 * ### Template overloading feature
	 * This controller have render paths setup for template overloading.
	 * This feature enables us to change views for some library that use `li3_backend` without
	 * changing original views, and ensure secure library updated without worries that something
	 * we've customized will be overwritten.
	 * This is list of paths arranged by priorities:
	 * _template_
	 *     <app>/views/<library>/<controller>/<template>.<type>.php
	 *     <current library>/views/<controller>/<template>.<type>.php
	 * _layout_
	 *     <app>/views/layouts/backend/<layout>.<type>.php
	 *     <li3_backend>/views/layouts/<layout>.<type>.php
	 * _element_
	 *     <app>/views/elements/<library>/<template>.<type>.php
	 *     <current library>/views/elements/<template>.<type>.php
	 *     <li3_backend>/views/elements/<template>.<type>.php
	 *
	 * <app> is your LITHIUM_APP_PATH
	 * <current library> is path to currently active library
	 * <library> is name of currently active library
	 * <li3_backend> is path to `li3_backend` library
	 *
	 * For example:
	 * Add `backend.html.php` to `<app>/views/layouts/backend` and you'll override `li3_backend's`
	 * default layout for `backend-component` setup.
	 *
	 * @param string $template `'partial-component'` or `'backend-component'`
	 */
	protected function _viewAs($template = null) {
		if ($template) {
			$backend = Libraries::get('li3_backend', 'path');
			$library = null;
			if (!empty($this->request->params['library'])) {
				$library = $this->request->params['library'];
			}
			$this->_render['paths'] = array(
				'template' => array(
					LITHIUM_APP_PATH . "/views/{$library}/{:controller}/{:template}.{:type}.php",
					'{:library}/views/{:controller}/{:template}.{:type}.php'
				),
				'layout'   => array(
					LITHIUM_APP_PATH . '/views/layouts/backend/{:layout}.{:type}.php',
					$backend . '/views/layouts/{:layout}.{:type}.php'
				),
				'element'  => array(
					LITHIUM_APP_PATH . "/views/elements/{$library}/{:template}.{:type}.php",
					'{:library}/views/elements/{:template}.{:type}.php',
					$backend . '/views/elements/{:template}.{:type}.php'
				)
			);
			if ($template === 'backend-component') $this->_render['layout'] = 'backend';
			if ($template === 'partial-component') $this->_render['layout'] = 'partial';
		}
	}

}

?>