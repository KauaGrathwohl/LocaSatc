<?php
if(!empty($url))
{
	$this->Paginator->options(array('url' => $url));
}
?><div class="paging">
	<?php echo $this->Paginator->prev('&laquo;', array('class' => 'setas prev', 'escape' => false), null, array('class'=>'disabled', 'escape' => false));?>
  	<?php echo $this->Paginator->numbers(array('modulus' => 12, 'separator' => ' '));?>
	<?php echo $this->Paginator->next('&raquo;', array('class' => 'setas next', 'escape' => false), null, array('class'=>'disabled', 'escape' => false));?>
</div>