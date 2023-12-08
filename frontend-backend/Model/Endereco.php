<?php 
App::uses('AppModel', 'Model');

class Endereco extends AppModel
{
    public $name = 'Endereco';
    public $useDbConfig = 'locasatc';
    public $useTable = 'enderecos';
}
?>