<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_backend\tests\cases\models;

use lithium\test\Unit;
use li3_backend\models\NavBar;

class NavBarTest extends Unit {

	public $reflection = null;

	public function getNavigationArray() {
		$navigation = $this->reflection->getStaticProperties();
		return $navigation['_navigation'];
	}

	public function setUp() {
		NavBar::reset();
		$model = '\li3_backend\models\NavBar';
		$this->reflection = new \ReflectionClass($model);
	}

	public function tearDown() {
		$this->reflection = null;
		NavBar::reset();
	}

	public function testGet() {
		$result = NavBar::get('home');
		$expected = false;
		$this->assertEqual($expected, $result);

		$result = NavBar::get('components');
		$expected = array(
			'title' => 'Components',
			'icon' => 'icon-wrench',
			'links' => array()
		);
		$this->assertEqual($expected, $result);

		$result = NavBar::get('dropdowns');
		$expected = array();
		$this->assertEqual($expected, $result);

		$result = NavBar::get('links');
		$expected = array();
		$this->assertEqual($expected, $result);

		$result = NavBar::get();
		$expected = array();
		$this->assertEqual($expected, $result);
	}

	public function testAddBackendHome() {
		NavBar::addBackendHome('li3_backend.Home::index');
		$result = $this->getNavigationArray();
		$expected = array(
			'title' => '<i class="icon-home icon-white"></i>',
			'url' => 'li3_backend.Home::index',
			'options' => array(
				'escape' => false
			)
		);
		$this->assertEqual($expected, $result['home']);

		NavBar::addBackendHome('li3_backend.Home::index', array('class' => 'test'));
		$result = $this->getNavigationArray();
		$expected = array(
			'title' => '<i class="icon-home icon-white"></i>',
			'url' => 'li3_backend.Home::index',
			'options' => array(
				'escape' => false,
				'class' => 'test'
			)
		);
		$this->assertEqual($expected, $result['home']);
	}

	public function testAddBackendLink() {
		NavBar::addBackendLink(array(
			'title' => 'Home', 'url' => 'li3_backend.Home::index'
		));
		$result = $this->getNavigationArray();
		$expected = array(
			'title' => 'Home',
			'url' => 'li3_backend.Home::index',
			'options' => array()
		);
		$this->assertEqual($expected, $result['navbar']['components']['links'][0]);

		NavBar::addBackendLink(array(
			'title' => 'HomeNew', 'url' => 'li3_backend.Home::new', 'options' => array('escape' => false)
		));
		$result = $this->getNavigationArray();
		$expected = array(
			'title' => 'HomeNew',
			'url' => 'li3_backend.Home::new',
			'options' => array('escape' => false)
		);
		$this->assertEqual($expected, $result['navbar']['components']['links'][1]);

		NavBar::addBackendLink(array(
			'title' => 'HomeTop', 'url' => 'li3_backend.Home::top',
		), false);
		$result = $this->getNavigationArray();
		$expected = array(
			'title' => 'HomeTop',
			'url' => 'li3_backend.Home::top',
			'options' => array()
		);
		$this->assertEqual($expected, $result['navbar']['links'][0]);
	}

	public function testAddDropdown() {
		NavBar::addDropdown('backend', array(
			'title' => 'Backend Info',
			'icon' => 'icon-wrench',
			'links' => array(
				array('title' => 'Application details', 'url' => 'li3_backend.Backend::details'),
				array('title' => 'About application', 'url' => 'li3_backend.Backend::about'),
				array('title' => 'Application version', 'url' => 'li3_backend.Backend::version')
			)
		));
		$result = $this->getNavigationArray();
		$expected = array(
			'title' => 'Backend Info',
			'icon' => 'icon-wrench',
			'links' => array(
				array('title' => 'Application details', 'url' => 'li3_backend.Backend::details'),
				array('title' => 'About application', 'url' => 'li3_backend.Backend::about'),
				array('title' => 'Application version', 'url' => 'li3_backend.Backend::version')
			)
		);
		$this->assertEqual($expected, $result['navbar']['dropdowns']['backend']);
	}

}

?>