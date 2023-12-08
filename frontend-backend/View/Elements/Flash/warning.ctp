<?php
$class = 'top-alert alert';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (strpos($class, 'alert-') === false) {
  $class .= ' alert-warning';
}
$class .= ' alert-dismissible';
?>
<div id="<?php echo h($key) ?>Message" class="<?php echo h($class) ?>" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo h($message) ?>
</div>
