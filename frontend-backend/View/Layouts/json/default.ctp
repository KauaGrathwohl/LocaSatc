<?php
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
header('Content-Type: application/json; charset=' . Configure::read('App.encoding'));

echo trim($this->fetch("content"));
exit;
?>
