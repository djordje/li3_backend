<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_backend\extensions\helper;

use lithium\net\http\Router;
use lithium\template\helper\Html;

/**
 * Class Backend is extended Html helper that provides integration with `Twitter Bootstrap`
 * and support auto adding `active` class to proper elements to highlight active link in menus.
 */
class Backend extends Html {

	/**
	 * String templates used by this helper.
	 *
	 * @var array
	 */
	protected $_strings = array(
		'nav-link' => '<li{:options}>{:link}</li>',
		'dropdown' => <<<EOF
<li class="dropdown{:active}">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#">
		{:icon}{:title} <b class="caret"></b>
	</a>
	<ul class="dropdown-menu">
		{:links}
	</ul>
</li>
EOF
	);

	/**
	 * Create navigation link compatible with `Twitter Bootstrap` markup.
	 * Instead of plain `<a/>` output this method wrap anchor in `<li/>`. If current url is url of
	 * wrapped `<a/>` add `active` class to `<li/>` wrapper.
	 * For example:
	 * {{{
	 *   $this->backend->nav('Test', '/');
	 *   // outputs:
	 *   // <li><a href="/">Test</a></li>
	 *   // if current url is url of anchor:
	 *   // <li class="active"><a href="/">Test</a></li>
	 * }}}
	 *
	 * @param $title
	 * @param mixed $url
	 * @param array $options Add following options to link:
	 * - `'wrapper-options'` _array_: Options that will be passed to `'nav-link'`
	 * - `'return'` _string_: Define would you like `'html'` output or `'array'` that contains two
	 * keys `'active'` _boolean_ and `'html'` used by `dropdown` method for example to know when to
	 * add `'active'` class to parent.
	 *
	 * @return array|string
	 *
	 * @see lithium\template\helper\Html::link()
	 */
	public function nav($title, $url = null, array $options = array()) {
		$defaults = array('wrapper-options' => array(), 'return' => 'html');
		list($scope, $options) = $this->_options($defaults, $options);

		$request = $this->_context->request();
		$currentUrl = $request->env('base') . $request->url;
		$matchedUrl = Router::match($url, $request);
		$active = false;

		if ($currentUrl === $matchedUrl || $currentUrl === $matchedUrl . '/index') {
			$active = true;
			if (isset($scope['wrapper-options']['class'])) {
				$scope['wrapper-options']['class'] .= ' active';
			} else {
				$scope['wrapper-options']['class'] = 'active';
			}
		}

		$link = $this->link($title, $url, $options);
		$html = $this->_render(__METHOD__, 'nav-link', array(
			'options' => $scope['wrapper-options'], 'link' => $link
		));
		if ($scope['return'] === 'html') return $html;
		if ($scope['return'] === 'array') return compact('active', 'html');
	}

	/**
	 * @param array $dropdown An array with following keys:
	 * - `'title'` _string_: Text that will be rendered as dropdown title.
	 * - `'icon'` _string_: This is __optional__ and represent class for desired
	 * `Twitter Bootstrap` icon.
	 * - `'links'` _array_: Array of links that compatible with `nav` method.
	 *
	 * For example:
	 * {{{
	 * $this->backend->dropdown(array(
	 *   'title' => 'Dropdown',
	 *   'icon' => 'icon-wrench',
	 *   'links' => array(
	 *     array('title' => 'Lithium', 'url' => 'http://lithify.me'),
	 *     'divider',
	 *     array('title' => 'Lithium 2', 'url' => 'http://lithify.me')
	 *   )
	 * ));
	 * // outputs:
	 * <li class="dropdown">
	 *   <a class="dropdown-toggle" data-toggle="dropdown" href="#">
	 *     <i class=""></i> Dropdown <b class="caret"></b>
	 *   </a>
	 *   <ul class="dropdown-menu">
	 *     <li><a href="http://lithify.me">Lithium</a></li>
	 *     <li class="divider"></li>
	 *     <li><a href="http://lithify.me">Lithium 2</a></li>
	 *   </ul>
	 * </li>
	 * }}}
	 *
	 * @return string Rendered `<li />` element with `Twitter Bootstrap` dropdown markup filled
	 */
	public function dropdown(array $dropdown) {
		extract($dropdown);
		$icon = (isset($icon) && $icon) ? '<i class="' . $icon . ' icon-white"></i> ' : '';
		$active = false;
		if (!empty($links)) {
			$renderedLinks = '';
			foreach ($links as $link) {
				if (is_string($link)) {
					$renderedLinks .= '<li class="' . $link . '"></li>';
				} else {
					isset($link['options']) || $link['options'] = array();
					$link['options'] += array('return' => 'array');
					$link = $this->nav($link['title'], $link['url'], $link['options']);
					if ($link['active']) $active = true;
					$renderedLinks .= $link['html'];
				}
			}
			$links = $renderedLinks;
		} else {
			$links = '';
		}
		if ($active) {
			$active = ' active';
		} else {
			$active = '';
		}
		return $this->_render(
			__METHOD__, 'dropdown',
			compact('icon', 'title', 'links', 'active'), array('escape' => false)
		);
	}

}

?>