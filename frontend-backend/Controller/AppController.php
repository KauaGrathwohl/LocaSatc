<?php
App::uses('Controller', 'Controller');
App::uses('CakeText', 'Utility');
App::uses('Security', 'Utility');

class AppController extends Controller
{
  public $Session;
  public $Auth;

  public $isAdmin = false;
  public $enabledAutoPopulateFields = true;

  public $uses = array('AdminUser', 'ExternalContent');
  public $helpers = array('Html', 'xHtml', 'Form', 'xForm', 'Util', 'Image');

  public $components = array(
    'Paginator',
    'Auth' => array(
      'authorize' => 'Controller',
      'authenticate' => array('Form'),
      'authError' => 'Área de Acesso Restrito, por favor informe suas credenciais para acesso.',
    ),
    'Session',
    'Cookie',
    'Flash',
    'DebugKit.Toolbar',
  );

  public $conf = array();
  public $mobileDetect;

  protected function __pageTitle()
  {
    if (!empty($this->viewVars['title']))
    {
      return sprintf("%s - %s", $this->viewVars['title'], $this->conf['title']);
    }

    return sprintf("%s - %s", $this->conf['title'], !$this->isAdmin ? $this->conf['subtitle'] : 'Gerenciador de Conteúdo');
  }

  public function beforeFilter()
  {
    $this->mobileDetect = new \Detection\MobileDetect();

    if (class_exists("Util"))
    {
      Util::$controller = $this;
    }

    /* COOKIES */
    $this->Cookie->name = 'burnbase';
    $this->Cookie->key = Configure::read('Security.salt');

    /* DYNAMICS CONFIG */
    $configs = Cache::read('configs', 'long');
    if (!$configs)
    {
      $configs = ClassRegistry::init("Configuration")->find("list", array('fields' => array('variable', 'value')));
      Cache::write('configs', $configs, 'long');
    }

    $this->conf = array_merge($this->conf, $configs);

    /* PURGE EXTERNAL CACHE */
    if (!isset($this->request->params['requested']))
    {
      $this->ExternalContent->purge();
    }

    /* ADMIN AUTH RULES */
    $this->isAdmin = (isset($this->request->params['prefix']) && $this->request->params['prefix'] === "admin");

    if ($this->isAdmin)
    {
      $this->cacheAction = false;

      AuthComponent::$sessionKey = 'Auth.AdminUser';

      $this->Auth->authenticate = array(
        AuthComponent::ALL => array(
          'userModel' => 'AdminUser',
          'scope' => array("AdminUser.active" => 'Y'),
          'passwordHasher' => 'Custom',
        ),
        'Form',
      );

      $this->Auth->loginAction = "/admin/cmanager/login";
      $this->Auth->loginRedirect = "/admin";

      $this->Auth->allow(
        "admin_upload_images",
        "admin_gallery"
      );

      if (empty($this->request->params['url']['ext']) || ($this->request->params['url']['ext'] !== "json" && $this->request->params['url']['ext'] !== "xml"))
      {
        $this->layout = "admin";
      }

      if ($this->request->isGet() && !isset($this->request->params['named']['nar']))
      {
        $r = $this->referer();
        $u = trim($this->request->url);
        if (!empty($r) && !empty($u) && $r != "/" && $u != "/" && strpos($r, $u) === false)
        {
          $rk = implode("_", array($this->request->controller, $this->request->action));
          $this->Session->write("Referer." . $rk, $r);
        }
      }
    }
    else
    {
      $this->Auth->allow();
    }

    if (isset($this->request->params['named']['preview']))
    {
      $this->cacheAction = false;
    }

    /* E-MAIL CONFIGURE */
    if (!empty($this->Email))
    {
      $this->Email->delivery = 'smtp';
      $this->Email->smtpOptions = 'smtp';
    }
    if (!empty($this->xEmail))
    {
      $this->xEmail->delivery = 'smtp';
      $this->xEmail->smtpOptions = 'smtp';
      if (config('email'))
      {
        $emailConf = new EmailConfig();
        $this->xEmail->from = $emailConf->smtp['from'];
      }
    }
  }

  public function beforeRender()
  {
    if (!empty($this->conf))
    {
      $this->set("conf", $this->conf);
    }

    if (!isset($this->viewVars['data']))
    {
      $this->set('data', $this->request->data);
    }

    $this->set('img_layout', "img/layout/");

    $this->set('has_http_error', strpos(strtolower($this->name), "error") !== false && (int) $this->response->statusCode() !== 200);
    $this->set('modelClass', $this->modelClass);
    $this->set('isAdmin', $this->isAdmin);

    $this->set("limits", array("10" => "10", "20" => "20", "50" => "50", "75" => "75", "100" => "100"));
    $this->set("ativos", array("S" => "Sim", "N" => "Não"));
    $this->set("actives", array("Y" => "Sim", "N" => "Não"));
    $this->set("visibles", array("Y" => "Sim", "N" => "Não"));
    $this->set("title_for_layout", $this->__pageTitle());

    /* Mobile Detect */
    $this->set(array(
      "is_mobile" => $this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet(),
      "is_tablet" => $this->mobileDetect->isTablet(),
      "is_ios" => $this->mobileDetect->isiOS(),
      "is_android" => $this->mobileDetect->isAndroidOS(),
    ));
  }

  public function flash($message, $url = null, $typeOrPause = 'set', $layout = 'flash')
  {
    if (!empty($message))
    {
      if ($typeOrPause == 'alert')
      {
        $typeOrPause = 'danger';
      }

      $this->Flash->{$typeOrPause}($message);
    }

    if (!empty($url))
    {
      return $this->redirect($url);
    }
  }

  public function autoReferer($url = null)
  {
    $rk = implode("_", array($this->request->controller, $this->request->action));
    if ($this->Session->check("Referer." . $rk))
    {
      $rurl = $this->Session->read("Referer." . $rk);
      if (!empty($rurl))
      {
        $url = $rurl;
        $this->Session->delete("Referer." . $rk);
      }
    }

    return $url;
  }

  public function loginReferer($url = null)
  {
    if (!empty($url))
    {
      $this->Session->write("Auth.referer", $url);
    }
    else
    {
      $url = $this->Session->read("Auth.referer");
      $this->Session->delete("Auth.referer");
    }

    return $url;
  }

  public function isAuthorized()
  {
    return true;
  }

  public function isModal()
  {
    return (isset($_REQUEST['_modal']) && $_REQUEST['_modal'] == true);
  }

  public function renderIframe()
  {
    Configure::write("debug", 0);

    $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    if ($xhr)
    {
      header('Content-Type: application/json; charset=' . Configure::read('App.encoding'));
    }
    else
    {
      header('Content-Type: text/plain; charset=' . Configure::read('App.encoding'));
    }
    header("Pragma: no-cache");
    header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");

    return $this->render(null, false, "/cmanager/admin_iframe");
  }

  public function freezeState($entity, $entity_id, $data)
  {
    $freezeData = isset($data[$entity]) ? $data[$entity] : $data;

    if (isset($freezeData['created']))
    {
      unset($freezeData['created']);
    }

    if (isset($freezeData['modified']))
    {
      unset($freezeData['modified']);
    }

    return $this->Session->write("FreezeState.{$entity}.{$entity_id}", $freezeData);
  }

  public function unfreezeState($entity, $entity_id)
  {
    return $this->Session->delete("FreezeState.{$entity}.{$entity_id}");
  }

  public function registerAudit($action, $name = null, $data = array(), $diff = false)
  {
    $this->loadModel('Audit');
    if (isset($data['entity']))
    {
      $this->loadModel($data['entity']);
    }

    $user_id = $this->Session->read('Auth.AdminUser.id');
    if (!empty($user_id))
    {
      if (empty($name) && $action === 'login' || $action === 'logout' || $action === 'access')
      {
        $name = $this->Session->read('Auth.AdminUser.name');
      }

      if ($diff !== false && isset($data['entity']) && isset($data['entity_id']) && isset($data['data']))
      {
        $freshData = $data['data'];
        unset($data['data']);

        $freezeData = $this->Session->read("FreezeState.{$data['entity']}.{$data['entity_id']}");
        if (!empty($freezeData))
        {
          $dataKeys = array_merge(array_keys($freezeData), array_keys($freshData));
          foreach ($dataKeys as $dk)
          {
            if (isset($data['skip']) && is_array($data['skip']) && in_array($dk, $data['skip']) !== false)
            {
              if (array_key_exists($dk, $freshData))
              {
                unset($freshData[$dk]);
              }

              if (array_key_exists($dk, $freezeData))
              {
                unset($freezeData[$dk]);
              }

            }
            elseif (array_key_exists($dk, $freshData) && is_array($freshData[$dk]))
            {
              $freshData[$dk] = $this->{$data['entity']}->deconstruct($dk, $freshData[$dk]);
            }
          }

          if (!isset($data['details']))
          {
            $data['details'] = array();
          }

          $data['details']['prev'] = array_diff_assoc($freezeData, $freshData);

          // unfreeze
          $this->unfreezeState($data['entity'], $data['entity_id']);
        }
      }

      $data = array_merge($data, compact('user_id', 'action', 'name'));
      if (isset($data['details']))
      {
        $data['details'] = json_encode($data['details']);
      }
      $data['ip'] = $this->request->clientIp();
      $data['session_id'] = $this->Session->id();

      $this->Audit->create();
      return $this->Audit->save($data);
    }

    return false;
  }

  public function getSearchConditions()
  {
    $conditions = array();

    if (!empty($this->request->query['query_type']) && !empty($this->request->query['query']))
    {
      if (strpos($this->request->query['query_type'], ".") === false)
      {
        $this->request->query['query_type'] = $this->modelClass . "." . $this->request->query['query_type'];
      }

      $is_date = false;
      $queryType = explode(".", $this->request->query['query_type']);
      if (isset($this->{$queryType[0]}))
      {
        $s = $this->{$queryType[0]}->schema();
        if (isset($s[$queryType[1]]) && isset($s[$queryType[1]]['type']))
        {
          $is_date = strpos(mb_strtolower($s[$queryType[1]]['type']), "date") !== false;
        }
      }

      if (!$is_date)
      {
        $query = str_replace(" ", "%", trim($this->request->query['query']));
        $conditions[$this->request->query['query_type'] . " LIKE"] = "%{$query}%";
      }
      else
      {
        $conditions["DATE({$this->request->query['query_type']})"] = Util::normalizeDate($this->request->query['query']);
      }
    }

    return $conditions;
  }

  public function autoPopulateFields()
  {
    // Populate belongTo select list vars
    foreach (array('belongsTo', 'hasAndBelongsToMany') as $type)
    {
      foreach (array_keys($this->{$this->modelClass}->$type) as $model)
      {
        if (is_array($this->{$this->modelClass}->$model->actsAs) && array_key_exists('Tree', $this->{$this->modelClass}->$model->actsAs))
        {
          $items = $this->{$this->modelClass}->$model->generateTreeList();
        }
        else
        {
          if (is_array($this->{$this->modelClass}->$model->displayField))
          {
            $order = implode(', ', $this->{$this->modelClass}->$model->displayField);
          }
          else
          {
            $order = $this->{$this->modelClass}->$model->alias . '.' . $this->{$this->modelClass}->$model->displayField;
          }

          if (!empty($this->{$this->modelClass}->$model->conditions))
          {
            $conditions = $this->{$this->modelClass}->$model->conditions;
          }
          else
          {
            $conditions = null;
          }

          if (!empty($this->{$this->modelClass}->$model->limit))
          {
            $limit = (int) $this->{$this->modelClass}->$model->limit;
          }
          else
          {
            $limit = null;
          }

          $items = $this->{$this->modelClass}->$model->find('list', compact('order', 'conditions', 'limit'));
        }
        $this->set(Inflector::underscore(Inflector::pluralize($model)), $items);
      }
    }
  }

  /* ADMIN BASE FUNCTIONS */
  public function admin_add()
  {
    if (!empty($this->request->data))
    {
      if ($this->{$this->modelClass}->save($this->request->data))
      {
        if (method_exists($this, 'updateGaleria'))
        {
          $this->updateGaleria($this->{$this->modelClass}->id);
        }

        /* ZERANDO O CACHE */
        Util::clearCache();

        $this->flash($this->{$this->modelClass}->name . ' processado(a) com sucesso!', array('action' => 'index'));
        return;
      }
      else
      {
        $this->flash('Por favor corrija os erros abaixo.');
      }
    }

    if ($this->enabledAutoPopulateFields)
    {
      $this->autoPopulateFields();
    }
    $this->set('add', true);
    $this->render('admin_edit');
  }

  public function admin_edit($id)
  {
    if (!$this->{$this->modelClass}->hasAny(array($this->{$this->modelClass}->primaryKey => $id)))
    {
      return $this->redirect(array('action' => 'index'));
    }

    if (!empty($this->request->data))
    {
      if ($this->{$this->modelClass}->save($this->request->data))
      {
        if (method_exists($this, 'updateGaleria'))
        {
          $this->updateGaleria($this->{$this->modelClass}->id);
        }

        /* ZERANDO O CACHE */
        Util::clearCache();

        $this->flash($this->{$this->modelClass}->name . ' processado(a) com sucesso!', $this->autoReferer(array('action' => 'index')));
        return;
      }
      else
      {
        $this->flash('Por favor corrija os erros abaixo.');
      }
    }
    else
    {
      $this->request->data = $this->{$this->modelClass}->read(null, $id);
    }

    if ($this->enabledAutoPopulateFields)
    {
      $this->autoPopulateFields();
    }
    $this->set('add', false);
  }

  public function admin_delete($id)
  {
    if ($this->{$this->modelClass}->delete($id))
    {
      /* ZERANDO O CACHE */
      Util::clearCache();

      $this->flash('Registro #' . $id . ' deletado com sucesso', $this->autoReferer(array('action' => 'index')));
    }
    else
    {
      $this->flash('Can\'t delete ' . $this->modelClass . ' with id ' . $id, $this->autoReferer(array('action' => 'index')));
    }
    return;
  }

  public function admin_index()
  {
    $conditions = array();
    if (empty($this->filterConditions))
    {
      $this->filterConditions = array();
    }

    if (!empty($this->request->data))
    {
      foreach ($this->request->data as $m => $dd)
      {
        foreach ($dd as $f => $v)
        {
          if (empty($v))
          {
            unset($this->request->data[$m][$f]);
          }

        }
      }

      if (empty($this->filterOperations))
      {
        $this->filterOperations = array();
      }

      $conditions = $this->postConditions($this->request->data, $this->filterOperations);
    }
    $conditions = array_merge($conditions, $this->filterConditions);

    if (!empty($this->paginate[$this->modelClass]))
    {
      $this->Paginator->settings = array_merge($this->Paginator->settings, $this->paginate[$this->modelClass]);
    }

    $items = $this->Paginator->paginate($this->modelClass, $conditions);
    $this->set(compact('items'));
  }

}
