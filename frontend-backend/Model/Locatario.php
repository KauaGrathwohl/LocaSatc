<?php 
App::uses('AppModel', 'Model');

class Locatario extends AppModel
{
    public $name = 'Locatario';
    public $useDbConfig = 'locasatc';
    public $useTable = 'locatario';
}
?>