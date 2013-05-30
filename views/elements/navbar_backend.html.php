<?php

/**
 * Navbar layout
 * |Home|Components|Dropdowns|Links|
 *
 * For example:
 * |Home|Components|CustomDropdown1|CustomDropdown2|Link1|Link2|
 *      |Link 1    |Custom link 1  |Custom link 4  |
 *      |Link 2    |Custom link 2  |
 *                 |Custom link 3  |
 */

use li3_backend\models\NavBar;

/**
 * Get navbar links
 */
$home = NavBar::get('home');
$components = Navbar::get('components');
$dropdowns = Navbar::get('dropdowns');
$links = Navbar::get('links');

/**
 * Home link if specified
 */
if ($home) echo $this->backend->nav($home['title'], $home['url'], $home['options']);

/**
 * Components dropdown menu
 */
echo $this->backend->dropdown($components);

/**
 * Additional dropdowns if specified
 */
if ($dropdowns) {
	foreach($dropdowns as $dropdown) {
		echo $this->backend->dropdown($dropdown);
	}
}

/**
 * Additional links if specified
 */
if ($links) {
	foreach($links as $link) {
		echo $this->backend->nav($link['title'], $link['url'], $link['options']);
	}
}

?>