<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_backend\models;

/**
 * Class NavBar is model for backend navigation bar.
 */
class NavBar {

	/**
	 * Navigation structure placeholder
	 *
	 * @var array
	 */
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

	/**
	 * Get desired link/links
	 *
	 * @param string $navigation Navigation identifier
	 * @return array
	 */
	public static function get($navigation = null) {
		if ($navigation === 'home') return static::$_navigation['home'];
		if ($navigation === 'components') return static::$_navigation['navbar']['components'];
		if ($navigation === 'dropdowns') return static::$_navigation['navbar']['dropdowns'];
		if ($navigation === 'links') return static::$_navigation['navbar']['links'];
		return array();
	}

	/**
	 * You should use this method only once to define your backend home link
	 *
	 * @param mixed $url Router match compatible _string_ or _array_
	 * @param array $options
	 */
	public static function addBackendHome($url = null, array $options = array()) {
		if ($url) {
			$title = '<i class="icon-home icon-white"></i>';
			$options += array('escape' => false);
			static::$_navigation['home'] = compact('title', 'url', 'options');
		}
	}

	/**
	 * Create a backend link.
	 *
	 * @param array $menuItem Available options are:
	 *     - `'title'` _string_: Link title
	 *     - `'url'` _mixed_: Router match compatible _string_ or _array_
	 *     - `'options'` _array_: Array of options to be used in helper
	 * @param mixed $parent Parent name specify where to add link:
	 *     - `false`: Add it to `'links'` array
	 *     - `'components'` default: Add it to links in components dropdown menu
	 *     - _string_: Add it to desired custom dropdown if exists dropdown with same named slug
	 */
	public static function addBackendLink(array $menuItem, $parent = 'components') {
		if (!empty($menuItem)) {
			$menuItem += array('options' => array());
			if ($parent) {
				if ($parent === 'components') {
					static::$_navigation['navbar']['components']['links'][] = $menuItem;
				}
				if (isset(static::$_navigation['navbar']['dropdowns'][$parent])) {
					static::$_navigation['navbar']['dropdowns'][$parent]['links'][] = $menuItem;
				}
			} else {
				static::$_navigation['navbar']['links'][] = $menuItem;
			}
		}
	}

	/**
	 * Create custom dropdown structure
	 *
	 * @param string $slug Dropdown identifier
	 * @param array $options Available options:
	 *     - `'title'` _string_: Dropdown link title
	 *     - `'icon'` _string_: Optionally you can add icon to link, this is `Twitter Bootstrap`
	 *     icon class.
	 *     - `'links'` _array_: Array of links that will be added to dropdown, each link is array with
	 *     `'title'` and `'url'` keys. Add _string_ `'divider'` in location where you want to render
	 *     divider `<li/>` with `Backend` helper.
	 *
	 * @see li3_backend\extensions\helper\Backend::dropdown()
	 */
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
