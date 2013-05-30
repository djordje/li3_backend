<?php

/**
 * @copyright Copyright 2013, Djordje Kovacevic (http://djordjekovacevic.com)
 * @license   http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * Left column menu
 *
 * By default `backend_component` and `partial_component` layouts are designed to have left
 * column menus.
 *
 * This element loads a few other elements to create left column:
 * <current controller>/component
 * <current controller>/<current layout>_component
 * component
 * <current layout>_component
 *
 * <current controller> is tabelized name of current controller, for example:
 *  `ManageUsersController` is `manage_users`
 *  `UsersController` is `users`
 *
 * <current layout> is name of currently active layout, `'partial'` or `'backend'`
 *
 * To learn about template overloading read:
 * @see li3_backend\extensions\controller\ComponentController::_viewAs()
 */

use lithium\util\Inflector;

$layout = $this->_config['layout'];
$controller = Inflector::tableize($this->_request->params['controller']);

/**
 * Render controller's general component menu if exists
 */
try {
	echo $this->_render('element', "{$controller}/component");
} catch(Exception $e) {}

/**
 * Render controller's component menu for current layout if exists
 */
try {
	echo $this->_render('element', "{$controller}/{$layout}_component");
} catch(Exception $e) {}

/**
 * Render library's general component menu if exists
 */
try {
	echo $this->_render('element', 'component');
} catch(Exception $e) {}

/**
 * Render library's component menu for current layout if exists
 */
try {
	echo $this->_render('element', "{$layout}_component");
} catch(Exception $e) {}

?>