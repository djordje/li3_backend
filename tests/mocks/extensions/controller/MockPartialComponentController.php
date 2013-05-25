<?php

namespace li3_backend\tests\mocks\extensions\controller;

use li3_backend\extensions\controller\ComponentController;

class MockPartialComponentController extends ComponentController {

	protected $_viewAs = 'partial-component';

	public function getRender() {
		return $this->_render;
	}

}

?>