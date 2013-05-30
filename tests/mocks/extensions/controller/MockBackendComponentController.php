<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_backend\tests\mocks\extensions\controller;

use li3_backend\extensions\controller\ComponentController;

class MockBackendComponentController extends ComponentController {

	protected $_viewAs = 'backend-component';

	public function getRender() {
		return $this->_render;
	}

}

?>