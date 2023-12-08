<div class="page-header">
  <div class="pull-right btn-group">
    <a href="<?php echo $this->xHtml->url(array('action' => 'index')); ?>" class="btn btn-info">Listar Imobiliarias</a>
  </div>
  <h2>Imobiliarias > <?php echo !$add ? "Editando" : "Adicionando"; ?></h2>
</div>

<?php echo $this->xForm->create('Imobiliaria', array('type' => 'file')); ?>
<fieldset>
  <?php

    if (!$add)
    {
      echo $this->xForm->input('id');
    }
    echo $this->xForm->input('franqueado', array());
    echo $this->xForm->input('status', array());
    echo $this->xForm->input('uf', array());
    echo $this->xForm->input('municipio', array());
    echo $this->xForm->input('bairro', array());
    echo $this->xForm->input('endereco', array());
  ?>
  <div class="form-actions col-md-12 text-center">
		<?php echo $this->xForm->submit('Salvar Imobiliaria', array('class' => 'btn btn-lg btn-primary', 'id' => 'btnSave')); ?>
	</div>
</fieldset>

<?php echo $this->xForm->end(); ?>