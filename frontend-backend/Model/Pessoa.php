<?php 
App::uses('AppModel', 'Model');

class Pessoa extends AppModel
{
    public $name = 'Pessoa';
    public $useDbConfig = 'locasatc';
    public $useTable = 'pessoas';
}
?>