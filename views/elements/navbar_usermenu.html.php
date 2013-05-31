<?php
/**
 * Dropdown user menu for component navbar
 * Require `li3_usermanager`
 */

use lithium\core\Libraries;
use lithium\security\Auth;

$user = false;
$dropdown = array(
	'title' => '',
	'icon' => '',
	'links' => array()
);

if (Libraries::get('li3_usermanager')) {
	$user = Auth::check('default');
	$avatar = $this->gravatar->image($user['email'], array(
		'default' => 'mm',
		'size' => 20
	));
	$dropdown['title'] = $avatar . ' ' . $user['username'];
	$dropdown['links'] = array(
		array(
			'title' => '<i class="icon-edit"></i> Edit details',
			'url' => 'li3_usermanager.Users::editDetails',
			'options' => array('escape' => false)
		),
		array(
			'title' => '<i class="icon-envelope"></i> Change email',
			'url' => 'li3_usermanager.Users::changeEmail',
			'options' => array('escape' => false)
		),
		array(
			'title' => '<i class="icon-lock"></i> Change password',
			'url' => 'li3_usermanager.Users::changePassword',
			'options' => array('escape' => false)
		),
		'divider',
		array(
			'title' => '<i class="icon-off"></i> Logout',
			'url' => 'li3_usermanager.Session::destroy',
			'options' => array('escape' => false)
		)
	);
}

if($user): ?>
	<ul class="nav pull-right">
		<?php echo $this->backend->dropdown($dropdown); ?>
	</ul>
<?php endif; ?>