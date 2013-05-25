<?php

use li3_backend\models\NavBar;

/**
 * Get navbar links
 */
$home = NavBar::get('home');
$components = Navbar::get('components');
$dropdowns = Navbar::get('dropdowns');
$links = Navbar::get('links');

if ($home) echo $this->backend->nav($home['title'], $home['url'], $home['options']);
echo $this->backend->dropdown($components);
if ($dropdowns) {
	foreach($dropdowns as $dropdown) {
		echo $this->backend->dropdown($dropdown);
	}
}
if ($links) {
	foreach($links as $link) {
		echo $this->backend->nav($link['title'], $link['url'], $link['options']);
	}
}

?>