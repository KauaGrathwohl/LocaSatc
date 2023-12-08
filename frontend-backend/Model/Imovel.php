<?php 
App::uses('AppModel', 'Model');

class Imovel extends AppModel
{
    public $name = 'Imovel';
    public $useDbConfig = 'locasatc';
    public $useTable = 'imoveis';
}
?>