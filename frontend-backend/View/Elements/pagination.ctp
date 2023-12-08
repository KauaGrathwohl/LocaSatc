<?php
if(!empty($url))
{
	$this->Paginator->options(array('url' => $url));
}
?>
<div class="hidden-print clearfix" style="text-align: center;">
    <ul class="pagination" style="margin: 6px auto;">
        <?php echo $this->Paginator->prev('&laquo;', array('class' => 'setas prev', 'escape' => false, 'tag' => 'li'), null, array('class'=>'disabled', 'escape' => false, 'tag' => 'li'));?>
        <?php echo $this->Paginator->numbers(array('modulus' => 12, 'separator' => ' ', 'tag' => 'li'));?>
        <?php echo $this->Paginator->next('&raquo;', array('class' => 'setas next', 'escape' => false, 'tag' => 'li'), null, array('class'=>'disabled', 'escape' => false, 'tag' => 'li'));?>
    </ul>
</div>
