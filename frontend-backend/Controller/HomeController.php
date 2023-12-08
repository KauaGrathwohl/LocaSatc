<?php
App::uses('AppController', 'Controller');

class HomeController extends AppController
{

  public $uses = array('Endereco', 'Imovel');

  public function index()
  {
    $this->Paginator->settings = array(
      'limit' => 10,
      'order' => array(
        'Imovel.id' => 'DESC'
        )
    );

    $imoveis = $this->Paginator->paginate('Imovel');

    $this->set(compact('imoveis'));
  }
}
