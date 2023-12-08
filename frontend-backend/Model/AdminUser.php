<?php
class AdminUser extends AppModel
{

  public $useTable = 'admin_users';

  public $order = array(
    'AdminUser.active DESC',
    'AdminUser.name ASC',
    'AdminUser.created DESC',
  );

  public $validate = array(
    'name' => array(
      'notBlank' => array(
        'rule' => 'notBlank',
        'message' => 'Informe um nome para contato',
      ),
    ),
    'username' => array(
      'notBlank' => array(
        'rule' => 'notBlank',
        'message' => 'Informe um nome para contato',
      ),
      'isUnique' => array(
        'rule' => array('isUnique'),
        'message' => 'Este usuário já está cadastrado em nosso sistema',
        'on' => 'create',
      ),
    ),
    'password' => array(
      'notBlank' => array(
        'rule' => 'notBlank',
        'message' => 'Informe uma senha válida',
        'on' => 'create',
      ),
      'minLength' => array(
        'rule' => array('minLength', 4),
        'message' => 'Sua senha deve ter pelo menos 4 caracteres',
        'on' => 'create',
      ),
    ),
  );

  public $types = array(
    'A' => 'Administrator',
    'N' => 'Normal',
  );

  public function passwordHasher()
  {
    App::uses('CustomPasswordHasher', 'Controller/Component/Auth');

    return new CustomPasswordHasher();
  }

  public function hashPasswords($data = null)
  {
    if (!empty($data))
    {
      if (!empty($data[$this->name]['password']))
      {
        $data[$this->name]['password'] = $this->passwordHasher()->hash($data[$this->name]['password']);
      }

      return $data;
    }
    return false;
  }

}
?>
