<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_backend\extensions\controller;

use lithium\action\Controller;
use lithium\core\Libraries;

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