<?php
class ExternalContent extends AppModel
{

  public $useTable = 'external_contents';

  public function purge()
  {
    return $this->query(sprintf("DELETE FROM {$this->useTable} WHERE expires IS NULL OR expires <= '%s'", date("Y-m-d H:i:s")));
  }

  public function fetchFromCache($options = array())
  {
    $options = array_merge(array(
      "url" => null,
      "data" => null,
      "method" => "GET",
    ), $options);

    $cacheExists = $this->find('first', array(
      'fields' => array("{$this->alias}.response"),
      'conditions' => array(
        "method" => $options['method'],
        "url" => $options['url'],
        "data" => is_string($options['data']) ? $options['data'] : json_encode($options['data']),
      ),
      'order' => array("{$this->alias}.created DESC"),
      'recursive' => -1,
    ));
    if (!empty($cacheExists))
    {
      return array_merge((array) json_decode($cacheExists[$this->alias]['response'], true), array("cached" => true));
    }

    return null;
  }

  public function fetch($options = array())
  {
    App::uses('HttpSocket', 'Network/Http');

    $options = array_merge(array(
      "url" => null,
      "data" => null,
      "method" => "GET",
      "headers" => array(),
      "request" => array(),
      "expires" => null,
    ), $options);

    $options['method'] = strtoupper($options['method']);

    $cachedResponse = $this->fetchFromCache($options);
    if (!empty($cachedResponse))
    {
      return $cachedResponse;
    }

    try {
      $socket = new HttpSocket(array(
        'ssl_verify_peer' => false,
        'ssl_allow_self_signed' => true,
        'ssl_verify_host' => false,
      ));

      $headers = array_merge($socket->request['header'], $options['headers']);
      $request = array_merge(compact('headers'), $options['request']);

      switch ($options['method'])
      {
        case 'GET':
          $responseData = $socket->get($options['url'], is_null($options['data']) ? array() : $options['data'], $request);
          break;
        case 'POST':
          $responseData = $socket->post($options['url'], is_null($options['data']) ? array() : $options['data'], $request);
          break;
        case 'PUT':
          $responseData = $socket->put($options['url'], is_null($options['data']) ? array() : $options['data'], $request);
          break;
        case 'DELETE':
          $responseData = $socket->delete($options['url'], is_null($options['data']) ? array() : $options['data'], $request);
          break;
        case 'PATCH':
          $responseData = $socket->patch($options['url'], is_null($options['data']) ? array() : $options['data'], $request);
          break;
        default:
          throw new Exception("Invalid method");
          break;
      }

      $response = array(
        "body" => $responseData->body(),
        "code" => $responseData->code,
        "error" => true,
      );
      if ($responseData->isOk())
      {
        $response['error'] = false;

        $expires = $options['expires'];
        if (!empty($expires))
        {
          if (is_string($expires))
          {
            $expires = strtotime($expires);
          }

          $expires = date("Y-m-d H:i:s", $expires);
        }

        $cacheData = array(
          "expires" => $expires,
          "method" => $options['method'],
          "url" => $options['url'],
          "data" => is_string($options['data']) ? $options['data'] : json_encode($options['data']),
          "response" => json_encode($response),
        );

        $this->create();
        $this->save($cacheData);
      }

      return $response;
    }
    catch (Exception $exp)
    {
      return array(
        "body" => null,
        "code" => null,
        "error" => true,
        "message" => $exp->getMessage(),
      );
    }
  }

}
