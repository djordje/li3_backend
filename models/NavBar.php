<?php

namespace li3_backend\models;

use lithium\core\StaticObject;

class NavBar extends StaticObject {

	protected static $_navigation = array(
		'home' => false,
		'navbar' => array(
			'components' => array(
				'title' => 'Components',
				'icon' => 'icon-wrench',
				'links' => array()
			),
			'dropdowns' => array(),
			'links' => array()
		)
	);

	public static function get($navigation = null) {
		if ($navigation === 'home') return static::$_navigation['home'];
		if ($navigation === 'components') return static::$_navigation['navbar']['components'];
		if ($navigation === 'dropdowns') return static::$_navigation['navbar']['dropdowns'];
		if ($navigation === 'links') return static::$_navigation['navbar']['links'];
		return array();
	}

	public static function addBackendHome($url = null, array $options = array()) {
		if ($url) {
			$title = '<i class="icon-home icon-white"></i>';
			$options += array('escape' => false);
			static::$_navigation['home'] = compact('title', 'url', 'options');
		}
	}

	public static function addBackendLink(array $menuItem, $parent = 'components') {
		if (!empty($menuItem)) {
			$menuItem += array('options' => array());
			if ($parent) {
				if (isset(static::$_navigation['navbar'][$parent])) {
					static::$_navigation['navbar'][$parent]['links'][] = $menuItem;
				}
			} else {
				static::$_navigation['navbar']['links'][] = $menuItem;
			}
		}
	}

	public static function addDropdown($slug, array $options = array()) {
		$options += array(
			'title' => '',
			'icon' => '',
			'links' => array()
		);
		static::$_navigation['navbar']['dropdowns'][$slug] = $options;
	}

	/**
	 * Reset navigation array to default values
	 * Primary for testing purpose
	 */
	public static function reset() {
		static::$_navigation = array(
			'home' => false,
			'navbar' => array(
				'components' => array(
					'title' => 'Components',
					'icon' => 'icon-wrench',
					'links' => array()
				),
				'dropdowns' => array(),
				'links' => array()
			)
		);
	}

}

?>