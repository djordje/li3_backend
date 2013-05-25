<?php

namespace li3_backend\tests\cases\extensions\controller;

use li3_backend\extensions\controller\ComponentController;
use li3_backend\tests\mocks\extensions\controller\MockBackendComponentController;
use li3_backend\tests\mocks\extensions\controller\MockPartialComponentController;
use lithium\test\Unit;
use lithium\core\Libraries;
use lithium\tests\mocks\action\MockControllerRequest;

class ComponentControllerTest extends Unit {

	public function testComponentViewRenderPaths() {
		$controller = new MockPartialComponentController();
		$render = $controller->getRender();
		$backend = Libraries::get('li3_backend', 'path');
		$expected = array(
			'template' => array(
				LITHIUM_APP_PATH . '{:library}/{:controller}/{:template}.{:type}.php',
				'{:library}/views/{:controller}/{:template}.{:type}.php'
			),
			'layout'   => array(
				LITHIUM_APP_PATH . '/views/layouts/backend/{:layout}.{:type}.php',
				$backend . '/views/layouts/{:layout}.{:type}.php'
			),
			'element'  => array(
				LITHIUM_APP_PATH . '/views/elements/{:library}/{:template}.{:type}.php',
				'{:library}/views/elements/{:template}.{:type}.php',
				$backend . '/views/elements/{:template}.{:type}.php'
			)
		);
		$this->assertEqual($expected, $render['paths']);
	}

	public function testPartialComponentView() {
		$controller = new MockPartialComponentController();
		$render = $controller->getRender();
		$this->assertEqual('partial', $render['layout']);
	}

	public function testBackendComponentView() {
		$controller = new MockBackendComponentController();
		$render = $controller->getRender();
		$this->assertEqual('backend', $render['layout']);
	}

	public function testBackendViewAutoSetup() {
		$request = new MockControllerRequest();
		$request->params['backend'] = true;
		$controller = new ComponentController(compact('request'));
		$reflection = new \ReflectionClass($controller);
		$result = $reflection->getProperty('_render');
		$result->setAccessible(true);
		$render = $result->getValue($controller);

		$backend = Libraries::get('li3_backend', 'path');
		$expected = array(
			'template' => array(
				LITHIUM_APP_PATH . '{:library}/{:controller}/{:template}.{:type}.php',
				'{:library}/views/{:controller}/{:template}.{:type}.php'
			),
			'layout'   => array(
				LITHIUM_APP_PATH . '/views/layouts/backend/{:layout}.{:type}.php',
				$backend . '/views/layouts/{:layout}.{:type}.php'
			),
			'element'  => array(
				LITHIUM_APP_PATH . '/views/elements/{:library}/{:template}.{:type}.php',
				'{:library}/views/elements/{:template}.{:type}.php',
				$backend . '/views/elements/{:template}.{:type}.php'
			)
		);
		$this->assertEqual($expected, $render['paths']);
		$this->assertEqual('backend', $render['layout']);
	}

}

?>