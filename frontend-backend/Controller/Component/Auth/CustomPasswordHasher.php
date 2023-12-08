<?php
App::uses('AbstractPasswordHasher', 'Controller/Component/Auth');
App::uses('Security', 'Utility');

class CustomPasswordHasher extends AbstractPasswordHasher
{

  public function hash($password)
  {
    return Security::hash($password, 'sha1', true);
  }

  public function check($password, $hashedPassword)
  {
    return $hashedPassword === $this->hash($password);
  }

}
