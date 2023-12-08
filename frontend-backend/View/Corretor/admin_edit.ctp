<div class="page-header">
  <div class="pull-right btn-group">
    <a href="<?php echo $this->xHtml->url(array('action' => 'index')); ?>" class="btn btn-info">Listar Corretores</a>
  </div>
  <h2>Corretor > <?php echo !$add ? "Editando" : "Adicionando"; ?></h2>
</div>

<?php echo $this->xForm->create('Corretor', array('type' => 'file')); ?>
<fieldset>
  <?php

    if (!$add)
    {
      echo $this->xForm->input('id');
    }
    echo $this->xForm->input('nome', array());
    echo $this->xForm->input('telefone', array());
    echo $this->xForm->input('email', array());
    echo $this->xForm->input('cargo', array());
    echo $this->xForm->input('unidade', array());
  ?>
  <div class="form-actions col-md-12 text-center">
		<?php echo $this->xForm->submit('Salvar Corretor', array('class' => 'btn btn-lg btn-primary', 'id' => 'btnSave')); ?>
	</div>
</fieldset>

<?php echo $this->xForm->end(); ?>