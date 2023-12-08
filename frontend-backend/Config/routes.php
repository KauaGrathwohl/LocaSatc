<?php
Router::connect('/', array('controller' => 'home', 'action' => 'index'));
Router::connect('/page/*', array('controller' => 'pages', 'action' => 'index'));
Router::connect('/noticia/*', array('controller' => 'noticias', 'action' => 'view'));
Router::connect('/fale-conosco', array('controller' => 'contato', 'action' => 'index'));

/* ADMIN ROUTE */
Router::connect(
  '/admin/:acts',
  array('prefix' => 'admin', 'controller' => 'cmanager', 'action' => 'index', 'acts' => null),
  array(
    'acts' => '(login|logout|index)',
  )
);

Router::parseExtensions();

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
