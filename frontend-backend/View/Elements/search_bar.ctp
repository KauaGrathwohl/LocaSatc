<?php
  if (!isset($action_filters))
  {
    $action_filters = array();
  }

  if (!isset($search_filters))
  {
    $search_filters = array();
  }

if (!empty($action_filters) || !empty($this->request->paging[$modelClass])): ?>
<div class="row hidden-print">
  <div class="btn-group pull-right hidden-xs hidden-print" role="group" style="margin-right: 15px;">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      <span class="glyphicon glyphicon-cog"></span>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <?php
        foreach ($action_filters as $param => $nparam):
          if (strpos($param, "dropdown-header") !== false && is_string($nparam))
        {
            echo '<li role="presentation" class="dropdown-header">' . $nparam . '</li>';
            continue;
          }

          if (strpos($param, "divider") !== false && $nparam === true)
        {
            echo '<li class="divider"></li>';
            continue;
          }

          $nvalue = true;
          if (is_array($nparam))
        {
            if (isset($nparam['label']))
          {
              $nlabel = $nparam['label'];
            }
          else
          {
              $nlabel = $param;
            }

            if (isset($nparam['value']))
          {
              $nvalue = $nparam['value'];
            }

          }
        else
        {
            $nlabel = $nparam;
          }

        ?>
			      <li class="<?php echo $this->xHtml->active_param(array("{$param}" => $nvalue)); ?>"><a href="<?php echo $this->xHtml->params_url(array("{$param}" => $nvalue), true); ?>"><?php echo $nparam; ?></a></li>
			      <?php endforeach;?>
<?php if (!empty($action_filters) && !empty($this->request->paging[$modelClass])): ?><li class="divider"></li><?php endif;?>
<?php if (!empty($this->request->paging[$modelClass])): ?>
<?php foreach ($limits as $k => $limit): ?>
          <li class="<?php echo ($k == $this->request->paging[$modelClass]['limit']) ? "active" : ""; ?>"><a href="<?php echo $this->xHtml->params_url(array('limit' => $k)); ?>"><?php echo $limit; ?> registros</a></li>
        <?php endforeach;?>
<?php endif;?>
    </ul>
  </div>
  <?php endif;

    if (!empty($search_filters)):
      if (!function_exists("fixp"))
    {
        function fixp($p)
      {
          if (!is_array($p))
        {
            return array("label" => $p, "placeholder" => "Buscar por " . $p);
          }

          return $p;
        }
      }

      reset($search_filters);

      $query_string = !empty($this->request->query['query']) ? $this->request->query['query'] : null;
      $query_type = !empty($this->request->query['query_type']) ? $this->request->query['query_type'] : key($search_filters);
      $first_filter = fixp($search_filters[$query_type]);

      if (!isset($search_url))
    {
        $search_url = array('controller' => $this->request->controller, 'action' => $this->request->action);
      }

      if (is_array($search_url))
    {
        $search_url = array_merge($search_url, $this->request->params['pass']);
      }

    echo $this->xForm->create($modelClass, array('url' => $search_url, 'type' => 'get', 'class' => 'col-xs-12 col-md-8'));
    ?><div class="input-group input-select input-select-short">
			      <div class="input-group-btn">
			        <a href="#" tabindex="-1" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-input="#<?php echo $modelClass; ?>QueryType">
			          <span class='value'><?php echo $first_filter['label']; ?></span>
			          <span class="caret"></span>
			        </a>
			        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
			          <?php foreach ($search_filters as $type => $nparam): $nparam = fixp($nparam);?>
						          <li><a tabindex="-1" data-type='<?php echo $type; ?>' data-placeholder="<?php echo $nparam['placeholder']; ?>" href="#"><?php echo $nparam['label']; ?></a></li>
						          <?php endforeach;?>
			        </ul>
			        <?php echo $this->xForm->input("{$modelClass}.query_type", array('type' => 'hidden', 'value' => $query_type)); ?>
			      </div>
			      <?php echo $this->xForm->input("{$modelClass}.query", array('div' => false, 'label' => false, 'value' => $query_string, 'class' => 'form-control input-query', 'placeholder' => $first_filter['placeholder'])); ?>
			      <span class="input-group-btn">
			        <button tabindex="-1" type="submit" id="btnSearchGlobal" class="btn btn-default" type="button">
			          <span class="glyphicon glyphicon-search"></span>
			        </button>
			        <?php if (!empty($query_string)): ?>
			        <a tabindex="-1" class="btn btn-link" href="<?php echo $this->xHtml->url($search_url); ?>" title="Limpar Busca e Filtros">
			          <span class="glyphicon glyphicon-remove"></span>
			        </a>
			        <?php endif;?>
      </span>
    </div>
  </form>
  <?php endif;?>
</div>
