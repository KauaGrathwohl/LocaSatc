<?php
App::uses('AppController', 'Controller');

class CmanagerController extends AppController
{
  public $uses = array('AdminUser');
  public $components = array('RequestHandler');

  public $paginate = array(
    'AdminUser' => array(
      'order' => array(
        'AdminUser.active' => 'DESC',
        'AdminUser.name' => 'ASC',
      ),
      'limit' => 25,
    ),
  );

  private function userTypesAccessAllowed($typesAllowed = array('A'))
  {
    $userType = $this->Session->read('Auth.AdminUser.type');
    foreach ($typesAllowed as $type)
    {
      if ($userType !== $type)
      {
        $this->redirect('/admin');
        return;
      }
    }
  }

  public function beforeFilter()
  {
    parent::beforeFilter();

    $this->set('types', $this->AdminUser->types);
  }

  public function admin_index()
  {
    $this->registerAudit("access");
  }

  public function admin_query()
  {
    $this->userTypesAccessAllowed();

    if (!empty($this->request->data['DB']['query']))
    {
      $retorno = array();
      $query = explode(";", $this->request->data['DB']['query']);
      foreach ($query as $i => $q)
      {
        if (($q = trim($q)) && !empty($q))
        {
          $retorno_single = $this->AdminUser->query($q);
          if (!empty($retorno_single))
          {
            if (is_array($retorno_single))
            {
              foreach ($retorno_single as $r)
              {
                $retorno[] = $r;
              }

            }
            else
            {
              $retorno[] = "Query #" . ($i + 1) . " executada com sucesso.";
            }
          }
        }
      }

      if (!empty($retorno))
      {
        $this->set(compact('retorno'));
        return $this->flash("SQL Query executada com sucesso!", null, "success");
      }
    }
  }

  public function admin_login()
  {
    if ($this->request->is('post'))
    {
      if ($this->Auth->login())
      {
        $this->registerAudit("login");

        if (!empty($this->request->data))
        {
          $user = $this->Session->read("Auth.AdminUser");
          $user['logoutAction'] = "/admin/cmanager/logout";

          $this->Session->write("Auth.AdminUser", $user);
        }

        return $this->redirect($this->Auth->redirect());
      }

      $this->flash('Erro ao efetuar seu acesso. Por favor confirme seus dados novamente.');
    }
  }

  public function admin_logout()
  {
    $this->autoRender = false;

    $this->registerAudit("logout");

    $this->Session->destroy();
    $this->Session->renew();

    return $this->flash("Você desconectou!", "/admin/cmanager/login");
  }

  public function admin_users()
  {
    $this->userTypesAccessAllowed();

    $this->modelClass = "AdminUser";

    $selected_type = array();

    $this->filterConditions = $this->getSearchConditions();
    if (!empty($this->request->named))
    {
      foreach ($this->request->named as $kn => $vn)
      {
        if (strpos($kn, "type_") !== false)
        {
          $a = explode("_", $kn);
          $type_id = end($a);

          $selected_type[] = $type_id;
        }
      }
    }

    if (!empty($selected_type))
    {
      $this->filterConditions['AdminUser.type'] = $selected_type;
    }

    $this->set('types', $this->AdminUser->types);

    parent::admin_index();
  }

  public function admin_user_add()
  {
    $this->userTypesAccessAllowed();

    $this->modelClass = "AdminUser";

    if (!empty($this->request->data['AdminUser']['username']))
    {
      if (empty($this->request->data['AdminUser']['password']))
      {
        unset($this->request->data['AdminUser']['password']);
        unset($this->request->data['AdminUser']['password_confirm']);
      }
      elseif (strlen($this->request->data['AdminUser']['password']) < 40)
      {
        $this->request->data['AdminUser']['password'] = $this->AdminUser->passwordHasher()->hash($this->request->data['AdminUser']['password']);
      }

      if ($this->AdminUser->saveAll($this->request->data))
      {
        $this->registerAudit("add", sprintf("Criação: %s", $this->request->data['AdminUser']['name']), array(
          "entity" => 'AdminUser',
          "entity_id" => $this->AdminUser->id,
        ));

        $this->flash("Dados atualizados com sucesso", array('action' => 'users'), "success");
        return;
      }
      else
      {
        unset($this->request->data['AdminUser']['password']);
      }
    }

    $this->set('add', true);

    $this->render('admin_user_edit');
  }

  public function admin_user_edit($id)
  {
    $this->userTypesAccessAllowed();

    $this->modelClass = "AdminUser";
    if (!empty($this->request->data['AdminUser']['username']))
    {
      $auditSkip = array();
      $baseData = $this->request->data['AdminUser'];

      if (empty($this->request->data['AdminUser']['password']))
      {
        $auditSkip[] = "password";

        unset($this->request->data['AdminUser']['password']);
        unset($this->request->data['AdminUser']['password_confirm']);
      }
      elseif (strlen($this->request->data['AdminUser']['password']) < 40)
      {
        $this->request->data['AdminUser']['password'] = $this->AdminUser->passwordHasher()->hash($this->request->data['AdminUser']['password']);
      }

      if ($this->AdminUser->saveAll($this->request->data))
      {
        $this->registerAudit("edit", sprintf("Edição: %s", $baseData['name']), array(
          "entity" => 'AdminUser',
          "entity_id" => $id,
          "data" => $baseData,
          "skip" => $auditSkip,
        ), true);

        if ($this->request->data['AdminUser']['id'] === $this->Session->read("Auth.AdminUser.id"))
        {
          unset($this->request->data['AdminUser']['password']);

          $this->request->data['AdminUser'] = array_merge($this->Session->read("Auth.AdminUser"), $this->request->data['AdminUser']);

          $this->Session->write("Auth.AdminUser", $this->request->data['AdminUser']);
        }

        $this->flash("Dados atualizados com sucesso", array('action' => 'users'), "success");
        return;
      }
      else
      {
        unset($this->request->data['AdminUser']['password']);
      }
    }

    $this->set('add', false);

    $this->request->data = $this->AdminUser->find('first', array(
      'conditions' => array(
        'AdminUser.id' => $id,
      ),
    ));
    if (empty($this->request->data))
    {
      $this->flash('Usuário não encontrado', $this->referer(), 'alert');
      return;
    }
    else
    {
      $this->freezeState('AdminUser', $id, $this->request->data);
      unset($this->request->data['AdminUser']['password']);
    }
  }

  public function admin_user_delete($id)
  {
    $this->userTypesAccessAllowed();

    $this->modelClass = "AdminUser";

    $label = $this->AdminUser->find('label', array('conditions' => compact('id')));

    $this->AdminUser->id = $id;
    if ($this->AdminUser->delete())
    {
      $this->unfreezeState('AdminUser', $id);

      $this->registerAudit("delete", sprintf("Exclusão: %s", $label), array(
        "entity" => 'AdminUser',
        "entity_id" => $id,
      ));

      $this->flash('Usuário deletado com sucesso', array('action' => 'users'), 'success');
      return;
    }

    $this->flash('Não foi possível deletar este usuário, tente novamente', array('action' => 'users'), 'warning');
    return;
  }

  public function admin_password()
  {
    if (!empty($this->request->data['AdminUser']['password']))
    {
      $this->request->data['AdminUser']['id'] = $this->Session->read("Auth.AdminUser.id");

      if (strlen($this->request->data['AdminUser']['password']) < 40)
      {
        $this->request->data['AdminUser']['password'] = $this->AdminUser->passwordHasher()->hash($this->request->data['AdminUser']['password']);
      }

      if ($this->AdminUser->save($this->request->data['AdminUser']))
      {
        $this->request->data = array();

        $this->flash('Nova senha definida com sucesso!', "/admin/cmanager/password/{$this->storeId}", "success");
        return;
      }
      else
      {
        $this->request->data = array();
      }
    }
  }

  /* ADMIN MISCELANIUS ACTIONS */
  public function admin_image_delete($model, $id, $field = 'img', $mode = 'file')
  {
    $this->autoLayout = false;
    $this->autoRender = false;

    if ($this->RequestHandler->isAjax())
    {
      Configure::write('debug', 0);

      $this->RequestHandler->respondAs('json');

      $this->header('Pragma: no-cache');
      $this->header('Cache-control: no-cache');
      $this->header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    if ($mode == 'file')
    {
      ClassRegistry::init($model)->deleteFile($field, $id);
    }
    elseif ($mode == 'entry')
    {
      ClassRegistry::init($model)->delete($id);
    }

    return json_encode(array("deleted" => true));
  }

  public function admin_save_field($model, $field = 'order')
  {
    $this->autoLayout = false;
    $this->autoRender = false;

    if ($this->RequestHandler->isAjax())
    {
      Configure::write('debug', 0);

      $this->RequestHandler->respondAs('json');

      $this->header('Pragma: no-cache');
      $this->header('Cache-control: no-cache');
      $this->header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    if (!empty($this->request->data[$model][$field]))
    {
      $thaModel = ClassRegistry::init($model);

      $os = explode(",", $this->request->data[$model][$field]);
      foreach ($os as $o)
      {
        $o = explode(":", $o);

        $thaModel->create();
        $thaModel->id = $o[0];
        $thaModel->saveField($field, $o[1]);
      }

      echo json_encode(array("saved" => true));
    }
    else
    {
      echo json_encode(array("saved" => false));
    }

    return;
  }
}
