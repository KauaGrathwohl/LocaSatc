<?php
class DATABASE_CONFIG
{

  /**
   * Em ambiente de desenvolvimento costumamos usar "localhost" no host local, isso quando o Mysql
   * roda direto na máquina, como por exemplo no caso do XAMPP ou Laragon. Mas no caso específico de
   * estar rodando este projeto em um container Docker, e o MySQL também estar rodando via docker, é
   * interessante que o `host` apontado abaixo seja o `host.docker.internal`, pois assim o PHP consegue
   * enxergar o MySQL que está rodando no container.
   *
   * Este comentário pode ser removido deste arquivo no momento que estivermos configurando o database.php
   * para funcionar "no ar" com as configurações do servidor on-line.
   */

  public $default = array(
    'datasource' => 'Database/Mysql',
    'persistent' => false,
    'host' => '', // em uso de docker 'host.docker.internal'; no XAMPP/Laragon 'localhost'
    'login' => '',
    'password' => '',
    'database' => '', // não se esqueça de definir uma dabatase própria para este novo projeto
    'encoding' => 'UTF8',
  );

  public $locasatc = array(
    'datasource' => 'Database/Mysql',
    'persistent' => false,
    'host' => '', // em uso de docker 'host.docker.internal'; no XAMPP/Laragon 'localhost'
    'login' => '',
    'password' => '',
    'database' => '', // não se esqueça de definir uma dabatase própria para este novo projeto
    'encoding' => 'UTF8',
  );

}
