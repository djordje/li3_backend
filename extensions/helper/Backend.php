<?php

namespace li3_backend\extensions\helper;

use lithium\net\http\Router;
use lithium\template\helper\Html;

class Backend extends Html {

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