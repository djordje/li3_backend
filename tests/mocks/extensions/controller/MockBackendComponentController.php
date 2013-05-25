<?php

namespace li3_backend\tests\mocks\extensions\controller;

use li3_backend\extensions\controller\ComponentController;

class MockBackendComponentController extends ComponentController {

	protected $_viewAs = 'backend-component';

	public function getRender() {
		return $this->_render;
	}

}

?>