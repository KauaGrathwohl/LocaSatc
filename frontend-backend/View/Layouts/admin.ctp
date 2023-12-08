<?php
header("Content-Type: text/html; charset=UTF-8"); // Charset
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // sempre modificada
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

$section = strtolower($this->name);
$action = strtolower($this->request->action);
?><!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<?php

			echo $this->Html->charset() . chr(10);

			echo $this->Html->tag('title', $this->fetch('title')) . chr(10);

			echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />' . chr(10);
			echo '<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />' . chr(10);
			echo '<link rel="dns-prefetch" href="//ajax.googleapis.com" />' . chr(10);
			echo '<link rel="dns-prefetch" href="//stackpath.bootstrapcdn.com" />' . chr(10);

			echo $this->Html->meta(array('name' => 'author', 'content' => 'Burn web.studio')) . chr(10);
			echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex, nofollow')) . chr(10);
			echo $this->Html->meta(array('name' => 'language', 'content' => 'pt-br')) . chr(10);
			echo $this->Html->meta(array('name' => 'revisit-after', 'content' => '1')) . chr(10);

			echo $this->Html->meta('favicon.ico', Router::url('/favicon.ico'), array('type' => 'icon')) . chr(10);

			$js = array(
				'admin/default',
				'//stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js',
				'/ckeditor/ckeditor.js',
				'/ckeditor/ckfinder/ckfinder.js',
				'/ckeditor/adapters/jquery.js',
			);
			if(file_exists(JS . 'admin' . DS . $section . ".js")) $js[] = 'admin' . DS . $section;

			$css = array(
				"//stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css",
				"admin/default",
			);
			if(file_exists(CSS . 'admin' . DS . $section . ".css")) $css[] = 'admin' . DS . $section;

			echo $this->Html->css($css) . $this->Html->script($js);
		?>
		<script>
		if(!window.$data) window.$data = {};
		<?php if(!empty($this->request->data)): ?>
		window.$data = Object.assign(window.$data, <?php echo json_encode($this->request->data); ?>);
		<?php endif;
		if(!empty($data_js)): ?>
		window.$data = Object.assign(window.$data, <?php echo json_encode($data_js); ?>);
		<?php endif; ?>
		window.$add = <?php echo isset($add) && !!$add ? "true" : "false"; ?>;
		</script>
	</head>

	<body id="<?php echo $section; ?>" class="<?php echo $action; ?>" data-uri="<?php echo Router::url("/"); ?>" data-webroot="<?php echo $this->request->webroot; ?>">
		<div id="activity" class="disabled">Carregando...</div>
		<div class="turbolinks-progress-bar"></div>
		<div id="page">
			<?php if($this->Session->check('Auth.AdminUser')): ?>
			<div class="navbar navbar-default navbar-inverse navbar-blue navbar-static-top">
				<div class="container-fluid">
					<div class="navbar-header">
						<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#top-navbar" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a title="<?php echo $conf['name']; ?>" href="<?php echo $this->xHtml->url("/admin"); ?>" class="navbar-brand hidden-md hidden-lg"><?php echo $conf['name']; ?></a>
					</div>
					<div id="top-navbar" class="navbar-collapse collapse">
						<ul id="nav" class="nav navbar-nav">
							<li><a href="<?php echo $this->Html->url("/admin"); ?>"><span class="glyphicon glyphicon-home"></span> Início</a></li>
							<?php if($this->Session->read('Auth.AdminUser.type') === 'A'): ?>
							<li><a href="<?php echo $this->Html->url("/admin/cmanager/users"); ?>"><span class="glyphicon glyphicon-user"></span> Usuários</a></li>
							<?php endif; ?>
							<li><a href="<?php echo $this->Html->url("/admin/corretor"); ?>"><span class=""></span> Corretor</a></li>
							<li><a href="<?php echo $this->Html->url("/admin/imobiliaria"); ?>"><span class=""></span> Imobiliario</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a title="<?php echo $this->Session->read("Auth.AdminUser.name"); ?>" class="dropdown-toggle" data-toggle="dropdown" href="#">
									<i class="glyphicon glyphicon-user"></i> <?php echo $this->Util->corta($this->Session->read("Auth.AdminUser.name"), 22); ?> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo $this->Html->url("/admin/cmanager/password"); ?>">Alterar minha senha</a></li>
									<li><a href="<?php echo $this->Html->url($this->Session->read("Auth.AdminUser.logoutAction")); ?>">Sair / Logout</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<header class="clearfix">
				<ul class="pull-right nav nav-pills">
					<li><a href="<?php echo Router::url("/", true); ?>" rel="external"><span class="glyphicon glyphicon-globe"></span> Acessar Site</a></li>
					<li><a href="<?php echo $this->Html->url($this->Session->read("Auth.AdminUser.logoutAction")); ?>"><span class="glyphicon glyphicon-off"></span> Sair</a></li>
				</ul>
				<h1 style="margin: 8px; padding: 0;" class="hidden-xs"><?php echo $conf['name']; ?></h1>
			</header>
			<?php endif; ?>
			<div class="content-container">
				<section id="content" class="container-fluid">
					<?php
					echo $this->Flash->render();
					echo $this->Flash->render('auth');

					echo $this->fetch("content");
					?>
				</section>
			</div>

			<footer>
				<div class="container-fluid">
					<div class="rights">
						<p>Copyright © <?php echo date("Y"); ?> <a href="http://www.burnweb.com.br/" rel="external" title="Desenvolvimento Burn web.studio">Burn web.studio</a></p>
					</div>
				</div>
			</footer>
		</div>
	</body>
</html>
