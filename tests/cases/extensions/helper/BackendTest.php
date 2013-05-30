<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_backend\tests\cases\extensions\helper;

use lithium\test\Unit;

use lithium\net\http\Router;
use lithium\action\Request;
use lithium\action\Response;
use lithium\tests\mocks\template\MockRenderer;
use li3_backend\extensions\helper\Backend;

class BackendTest extends Unit {

	/**
	 * Test object instance
	 *
	 * @var object
	 */
	public $backend = null;

	protected $_routes = array();

	/**
	 * Initialize test by creating a new object instance with a default context.
	 */
	public function setUp() {
		$this->_routes = Router::get();
		Router::reset();
		Router::connect('/test', 'Test::index');
		Router::connect('/test2', 'TestTwo::index');

		$this->context = new MockRenderer(array(
			'request' => new Request(array(
				'url' => '/test',
				'env' => array('HTTP_HOST' => 'foo.local')
			)),
			'response' => new Response()
		));
		$this->backend = new Backend(array('context' => &$this->context));
	}

	/**
	 * Clean up after the test.
	 */
	public function tearDown() {
		Router::reset();
		foreach ($this->_routes as $scope => $routes) {
			Router::scope($scope, function() use ($routes) {
				foreach ($routes as $route) {
					Router::connect($route);
				}
			});
		}
		unset($this->backend);
	}

	public function testNav() {
		$result = $this->backend->nav('Test', 'Test::index');
		$this->assertEqual('<li class="active">link</li>', $result);

		$result = $this->backend->nav(
			'Test', 'Test::index', array('wrapper-options' => array('class' => 'test'))
		);
		$this->assertEqual('<li class="test active">link</li>', $result);

		$result = $this->backend->nav('TestTwo', 'TestTwo::index');
		$this->assertEqual('<li>link</li>', $result);

		$result = $this->backend->nav(
			'TestTwo', 'TestTwo::index', array('wrapper-options' => array('class' => 'test'))
		);
		$this->assertEqual('<li class="test">link</li>', $result);
	}

	public function testDropdown() {
		$template = array(
			"<li class=\"dropdown\">\n",
			"<li class=\"dropdown active\">\n",

			"\t<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">\n",

			"\t</a>\n" . "\t<ul class=\"dropdown-menu\">\n",

			"\t</ul>\n" . "</li>"
		);

		$result = $this->backend->dropdown(array(
			'title' => 'Components',
			'icon' => '',
			'links' => array()
		));
		$title = "\t\tComponents <b class=\"caret\"></b>\n";
		$links = "\t\t\n";
		$expected = $template[0] . $template[2] . $title . $template[3] . $links . $template[4];
		$this->assertEqual($expected, $result);

		$result = $this->backend->dropdown(array(
			'title' => 'Components',
			'icon' => 'icon-wrench',
			'links' => array()
		));
		$title = "\t\t<i class=\"icon-wrench icon-white\"></i> Components <b class=\"caret\"></b>\n";
		$links = "\t\t\n";
		$expected = $template[0] . $template[2] . $title . $template[3] . $links . $template[4];
		$this->assertEqual($expected, $result);

		$result = $this->backend->dropdown(array(
			'title' => 'Components',
			'icon' => '',
			'links' => array(
				array('title' => 'Home', 'url' => 'Test::index'),
				array('title' => 'About', 'url' => 'TestTwo::index')
			)
		));
		$title = "\t\tComponents <b class=\"caret\"></b>\n";
		$links = "\t\t<li class=\"active\">link</li><li>link</li>\n";
		$expected = $template[1] . $template[2] . $title . $template[3] . $links . $template[4];
		$this->assertEqual($expected, $result);
		$result = $this->backend->dropdown(array(
			'title' => 'Components',
			'icon' => '',
			'links' => array(
				array('title' => 'Home', 'url' => 'Test::index'),
				'divider',
				array('title' => 'About', 'url' => 'TestTwo::index')
			)
		));
		$title = "\t\tComponents <b class=\"caret\"></b>\n";
		$links = "\t\t<li class=\"active\">link</li><li class=\"divider\"></li><li>link</li>\n";
		$expected = $template[1] . $template[2] . $title . $template[3] . $links . $template[4];
		$this->assertEqual($expected, $result);
	}

}

?>