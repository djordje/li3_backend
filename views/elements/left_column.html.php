<?php

$template = '';
$componentMenu = array(
	'start' => '<ul class="nav nav-list well"><li class="nav-header">Component Navigation</li>',
	'content' => false,
	'end' => '</ul>'
);

try {
	$componentMenu['content'] = $this->_render('element', $this->_config['layout'] . '_component');
} catch(Exception $e){}

if ($componentMenu['content']) {
	$template .= $componentMenu['start'] . $componentMenu['content'] . $componentMenu['end'];
}

try {
	$template = $this->_render('element', 'component');
} catch(Exception $e){}

echo $template;

?>