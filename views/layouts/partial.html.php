<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $this->title(); ?> | <?php echo LITHIUM_APP_NAME; ?></title>
	<?php echo $this->html->style(array(
		'/assets/backend/css/bootstrap',
		'/assets/backend/css/bootstrap-fix',
		'/assets/backend/css/bootstrap-responsive'
	)); ?>
	<?php echo $this->html->script(array(
		'/assets/backend/js/jquery-1.10.1.min',
		'/assets/backend/js/bootstrap.min',
		'/assets/backend/js/confirm-action'
	)); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<?php echo $this->html->link(LITHIUM_APP_NAME, '/', array('class' => 'brand')); ?>
				<div class="nav-collapse collapse">
					<?php echo $this->_render('element', 'navbar_usermenu'); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<?php echo $this->_render('element', 'left_column'); ?>
			</div>
			<div class="span9">
				<?php echo $this->content(); ?>
			</div>
		</div>
	</div>
</body>
</html>