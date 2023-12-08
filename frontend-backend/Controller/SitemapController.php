<?php
App::uses('AppController', 'Controller');

class SitemapController extends AppController
{

  public $uses = array();

  public $components = array('RequestHandler');

  public function index()
  {
    $this->autoLayout = false;

    $items = array();
    $items[] = array("loc" => Router::url('/', true), "changefreq" => "daily", "priority" => "1.0");
    $items[] = array("loc" => Router::url('/contato', true), "changefreq" => "monthly", "priority" => "0.5");

    $this->set(compact('items'));
    $this->render('index');
  }

}
