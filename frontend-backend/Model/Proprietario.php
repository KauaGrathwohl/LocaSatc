<?php 
App::uses('AppModel', 'Model');

class Proprietario extends AppModel
{
    public $name = 'Proprietario';
    public $useDbConfig = 'locasatc';
    public $useTable = 'proprietario';
}
?>